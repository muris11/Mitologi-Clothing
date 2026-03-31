
"use client";

import Cookies from "js-cookie";
import { addToCart, createCart, getCart, removeFromCart, updateCart } from "lib/api";
import { Cart, UnknownError } from "lib/api/types";
import { createContext, ReactNode, useContext, useEffect, useState } from "react";
import { useToast } from "components/ui/ultra-quality-toast";

interface CartContextType {
  cart: Cart | undefined;
  isLoading: boolean;
  addToCart: (merchandiseId: string, quantity: number, productDetails?: OptimisticProductDetails) => Promise<void>;
  removeFromCart: (lineId: string) => Promise<void>;
  updateQuantity: (lineId: string, merchandiseId: string, quantity: number) => Promise<void>;
  openCart: () => void;
  closeCart: () => void;
  isCartOpen: boolean;
}

type OptimisticProductDetails = {
  id: string;
  handle: string;
  title?: string;
  price?: string;
  featuredImage: Cart["lines"][number]["merchandise"]["product"]["featuredImage"];
};

const CartContext = createContext<CartContextType | undefined>(undefined);

export function CartProvider({ children }: { children: ReactNode }) {
  const [cart, setCart] = useState<Cart | undefined>(undefined);
  const [isLoading, setIsLoading] = useState(true);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const { addToast } = useToast();

  const refreshCart = async () => {
    try {
      let cartId = Cookies.get("cartSessionId");
      let cartData: Cart | undefined;

      if (cartId) {
        cartData = await getCart(cartId);
      }

      setCart(cartData);
    } catch (error) {
      console.error("Failed to refresh cart:", error);
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    refreshCart();
  }, []);

  // Listen for auth state changes (login/logout) to refresh cart
  useEffect(() => {
    const handleAuthChange = () => {
      // Small delay to allow auth state to settle
      setTimeout(() => {
        refreshCart();
      }, 100);
    };

    window.addEventListener('auth:changed', handleAuthChange);
    return () => window.removeEventListener('auth:changed', handleAuthChange);
  }, []);

  const openCart = () => setIsCartOpen(true);
  const closeCart = () => setIsCartOpen(false);

  const addItem = async (merchandiseId: string, quantity: number, productDetails?: OptimisticProductDetails) => {
    setIsLoading(true);
    const previousCart = cart; // Store previous cart for rollback

    // --- Optimistic UI Update ---
    if (cart && productDetails) {
      // Very rough optimistic item structure to make the UI tick instantly
      const optimisticLineItem = {
        id: `temp-${Date.now()}`,
        quantity,
        cost: {
          totalAmount: { amount: String(parseFloat(productDetails.price || "0") * quantity), currencyCode: "IDR" }
        },
        merchandise: {
          id: merchandiseId,
          title: "Menambahkan...",
          selectedOptions: [],
          product: {
            id: productDetails.id,
            handle: productDetails.handle,
            title: productDetails.title || "Menambahkan...",
            featuredImage: productDetails.featuredImage
          }
        }
      };

      setCart({
        ...cart,
        lines: [...cart.lines, optimisticLineItem],
        totalQuantity: cart.totalQuantity + quantity,
      });
      openCart();
    }
    // ----------------------------

    try {
      let cartId = Cookies.get("cartSessionId");
      let currentCart = cart;

      if (!cartId) {
        currentCart = await createCart();
        // Prefer UUID sessionId for stable cart merging on login; fall back to integer id
        cartId = currentCart.sessionId || currentCart.id;
        if (cartId) {
            Cookies.set("cartSessionId", cartId, { expires: 7, sameSite: 'Lax', secure: process.env.NODE_ENV === 'production', path: '/' });
        }
      }

      if (cartId) {
        const updatedCart = await addToCart(cartId, [{ merchandiseId, quantity }]);
        setCart(updatedCart);
        if (!cart) openCart(); // open if it wasn't opened optimistically
        addToast({ variant: "success", title: "Produk berhasil ditambahkan ke keranjang" });
      }
    } catch (e: unknown) {
      const err = e as UnknownError;
      setCart(previousCart); // Rollback
      addToast({ variant: "error", title: err?.message || "Gagal menambahkan ke keranjang." });
    } finally {
      setIsLoading(false);
    }
  };

  const removeItem = async (lineId: string) => {
    setIsLoading(true);
    const previousCart = cart; // Rollback point

    // --- Optimistic UI Update ---
    if (cart) {
      setCart({
        ...cart,
        lines: cart.lines.filter(line => line.id !== lineId),
        // Accurate total count requires recalculation, ignoring for simple optimisim
      });
    }

    try {
      const cartId = Cookies.get("cartSessionId");
      if (cartId) {
        try {
          const updatedCart = await removeFromCart(cartId, [lineId]);
          setCart(updatedCart);
          addToast({ variant: "success", title: "Item dihapus dari keranjang" });
        } catch (error: unknown) {
          const err = error as UnknownError;
          // If item not found (404) or already deleted, just refresh cart
          if (err?.status === 404 || err?.message?.includes('No query results')) {
            await refreshCart();
          } else {
            throw error;
          }
        }
      }
    } catch (e: unknown) {
      const err = e as UnknownError;
      setCart(previousCart); // Rollback on error
      addToast({ variant: "error", title: err?.message || "Gagal menghapus item dari keranjang." });
    } finally {
      setIsLoading(false);
    }
  };

  const updateItem = async (lineId: string, merchandiseId: string, quantity: number) => {
    setIsLoading(true);
    const previousCart = cart;

    // --- Optimistic UI Update ---
    if (cart) {
      setCart({
        ...cart,
        lines: cart.lines.map(line => 
          line.id === lineId ? { ...line, quantity } : line
        )
      });
    }

    try {
      const cartId = Cookies.get("cartSessionId");
      if (cartId) {
        try {
          const updatedCart = await updateCart(cartId, [{ id: lineId, merchandiseId, quantity }]);
          setCart(updatedCart);
        } catch (error: unknown) {
          const err = error as UnknownError;
          // If item not found (404) or already deleted, just refresh cart
          if (err?.status === 404 || err?.message?.includes('No query results')) {
            await refreshCart();
          } else {
            throw error;
          }
        }
      }
    } catch (e: unknown) {
      const err = e as UnknownError;
      setCart(previousCart); // Rollback
      addToast({ variant: "error", title: err?.message || "Gagal mengubah kuantitas." });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <CartContext.Provider value={{ 
      cart, 
      isLoading, 
      addToCart: addItem, 
      removeFromCart: removeItem, 
      updateQuantity: updateItem,
      openCart,
      closeCart,
      isCartOpen
    }}>
      {children}
    </CartContext.Provider>
  );
}

export function useCart() {
  const context = useContext(CartContext);
  if (context === undefined) {
    throw new Error("useCart must be used within a CartProvider");
  }
  return context;
}
