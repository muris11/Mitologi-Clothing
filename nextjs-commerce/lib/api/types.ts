export type Maybe<T> = T | null;

export type Cart = {
  id: string | undefined;
  sessionId?: string;
  checkoutUrl: string;
  cost: {
    subtotalAmount: Money;
    totalAmount: Money;
    totalTaxAmount: Money;
  };
  lines: CartItem[];
  totalQuantity: number;
};

export type CartProduct = {
  id: string;
  handle: string;
  title: string;
  featuredImage: Image;
};

export type CartItem = {
  id: string | undefined;
  quantity: number;
  cost: {
    totalAmount: Money;
  };
  merchandise: {
    id: string;
    title: string;
    selectedOptions: {
      name: string;
      value: string;
    }[];
    product: CartProduct;
  };
};

export type Collection = {
  handle: string;
  title: string;
  description: string;
  seo: SEO;
  path: string;
  updatedAt: string;
};

export type Image = {
  url: string;
  altText: string;
  width: number;
  height: number;
};

export type Menu = {
  title: string;
  path: string;
};

export type Money = {
  amount: string;
  currencyCode: string;
};

export type Page = {
  id: string;
  title: string;
  handle: string;
  body: string;
  bodySummary: string;
  seo?: SEO;
  createdAt: string;
  updatedAt: string;
};

export type Product = {
  id: string;
  handle: string;
  availableForSale: boolean;
  title: string;
  description: string;
  descriptionHtml: string;
  options: ProductOption[];
  priceRange: {
    maxVariantPrice: Money;
    minVariantPrice: Money;
  };
  variants: ProductVariant[];
  featuredImage: Image;
  images: Image[];
  seo: SEO;
  tags: string[];
  totalStock?: number;
  updatedAt: string;
  is_wishlisted?: boolean;
  averageRating?: number;
  totalReviews?: number;
  totalSold?: number;
};

export type ProductOption = {
  id: string;
  name: string;
  values: string[];
};

export type ProductVariant = {
  id: string;
  title: string;
  availableForSale: boolean;
  selectedOptions: {
    name: string;
    value: string;
  }[];
  price: Money;
  sku?: string; // optional SKU returned by API
  stock?: number; // optional stock/inventory quantity
};

export type SEO = {
  title: string;
  description: string;
};

export type User = {
  id: number;
  name: string;
  email: string;
  role?: string;
  avatar_url?: string;
  phone?: string;
  addresses?: Address[];
  address?: string;
  city?: string;
  province?: string;
  postal_code?: string;
  created_at?: string;
  updated_at?: string;
};

export type Address = {
  id: number;
  label: string;
  recipientName: string;
  phone: string;
  addressLine1: string;
  addressLine2?: string;
  city: string;
  province: string;
  postalCode: string;
  country: string;
  isPrimary: boolean;
};

export type ReviewItem = {
  id: number;
  product_id: number;
  user_id: number;
  user_name: string;
  user_avatar?: string;
  rating: number; // 1-5
  comment: string;
  admin_reply?: string;
  admin_replied_at?: string;
  created_at: string;
};

export type ReviewSummary = {
  average_rating: number;
  total_reviews: number;
  rating_breakdown: Record<string, number>;
};

export type WishlistItem = {
  id: number;
  product_id: number;
  product: Product;
  created_at: string;
};

export type OrderItem = {
  id: number;
  product_title: string;
  variant_title: string;
  price: number;
  quantity: number;
  total: number;
  product_handle?: string;
  product_image?: string;
};

export type Order = {
  id: number;
  orderNumber: string;
  status:
    | "pending"
    | "paid"
    | "processing"
    | "shipped"
    | "delivered"
    | "completed"
    | "cancelled"
    | "refunded";
  paymentStatus?: string;
  total: number;
  subtotal: number;
  shippingCost: number;
  shippingAddress?: {
    name?: string;
    phone?: string;
    address?: string;
    city?: string;
    province?: string;
    postalCode?: string;
  };
  itemsCount?: number;
  createdAt: string;
  refundRequestedAt?: string;
  refundReason?: string;
  items: OrderItem[];
};

export type HeroSlide = {
  id: number;
  title: string;
  subtitle: string;
  image_url: string;
  cta_text: string;
  cta_link: string;
  sort_order: number;
  is_active: boolean;
};

export type Feature = {
  id: number;
  title: string;
  description: string;
  icon: string;
  sort_order: number;
};

export type Testimonial = {
  id: number;
  name: string;
  role: string;
  content: string;
  rating: number;
  avatar_url: string | null;
  is_active: boolean;
};

export type Material = {
  id: number;
  name: string;
  description: string;
  color_theme: string;
  sort_order: number;
};

export type PortfolioItem = {
  id: number;
  title: string;
  slug: string;
  category: string;
  image_url: string;
  description?: string;
  sort_order: number;
  is_active: boolean;
};

export type OrderStep = {
  id: number;
  step_number: number;
  title: string;
  description: string;
  type: "langsung" | "ecommerce";
  sort_order: number;
};

export type CtaData = {
  title: string;
  subtitle: string;
  button_text: string;
  button_link: string;
};

export type Category = {
  id: number;
  name: string;
  slug: string;
  handle: string;
  description: string | null;
  image: string | null;
  products_count?: number;
};

export type SiteSettings = {
  general?: {
    site_name?: string;
    site_tagline?: string;
    site_description?: string;
    site_logo?: string;
    company_founded_year?: string;
  };
  about?: {
    about_headline?: string;
    company_founded_year?: string;
    about_description_1?: string;
    about_description_2?: string;
    about_short_history?: string;
    about_logo_meaning?: string;
    about_logo_meaning_detailed?: { letter: string; description: string }[];
    about_image?: string;
    vision_text?: string;
    mission_text?: string;
    values_text?: string;
    legal_company_name?: string;
    legal_address?: string;
    legal_business_field?: string;
    legal_npwp?: string;
    legal_nib?: string;
    legal_nmid?: string;
    // Founder Story
    founder_name?: string;
    founder_role?: string;
    founder_story?: string;
    founder_photo?: string;
  };
  vision_mission?: {
    vision_text?: string;
    mission_text?: string;
    values_text?: string;
  };
  legality?: {
    legal_company_name?: string;
    legal_address?: string;
    legal_business_field?: string;
    legal_npwp?: string;
    legal_nib?: string;
    legal_nmid?: string;
  };
  // Dynamic arrays from JSON
  services_data?: {
    title: string;
    desc: string;
    image: string;
    materials?: string;
    keunggulan?: string;
  }[];
  guarantees_data?: {
    title: string;
    description?: string;
    desc?: string;
  }[];
  company_values_data?: {
    title: string;
    description?: string;
    desc?: string;
  }[];
  // Deprecated fixed fields (optional for backward compatibility)
  guarantee?: {
    guarantee_1_title?: string;
    guarantee_1_desc?: string;
    guarantee_2_title?: string;
    guarantee_2_desc?: string;
    guarantee_3_title?: string;
    guarantee_3_desc?: string;
  };
  services?: {
    service_1_title?: string;
    service_1_desc?: string;
    service_1_image?: string;
    service_2_title?: string;
    service_2_desc?: string;
    service_2_image?: string;
    service_3_title?: string;
    service_3_desc?: string;
    service_3_image?: string;
  };
  pricing?: {
    pricing_min_order?: string;
    pricing_plastisol_data?: string; // JSON string
    pricing_addons_data?: string; // JSON string
  };
  cta?: {
    cta_title?: string;
    cta_subtitle?: string;
    cta_button_text?: string;
    cta_button_link?: string;
  };
  contact?: {
    contact_email?: string;
    contact_phone?: string;
    contact_address?: string;
    contact_maps_embed?: string;
    contact_whatsapp?: string;
    contact_operational_hours?: string;
    operating_hours_weekday_label?: string;
    operating_hours_weekday?: string;
    operating_hours_weekend_label?: string;
    operating_hours_weekend?: string;
    social_instagram?: string;
    social_instagram_enabled?: string; // '1' or '0'
    social_tiktok?: string;
    social_tiktok_enabled?: string;
    social_facebook?: string;
    social_facebook_enabled?: string;
    social_shopee?: string;
    social_shopee_enabled?: string;
    social_twitter?: string;
    social_twitter_enabled?: string;
    whatsapp_number?: string;
  };
  seo?: {
    seo_meta_title?: string;
    seo_meta_description?: string;
    seo_og_image?: string;
  };
};

export type TeamMember = {
  id: number;
  name: string;
  position: string;
  photo_url: string | null;
  parent_id: number | null;
  level: number;
  sort_order: number;
};

export type Partner = {
  id: number;
  name: string;
  logo: string;
  website_url?: string;
  description?: string;
  is_active: boolean;
  sort_order: number;
};

export type PrintingMethod = {
  id: number;
  name: string;
  slug: string;
  description: string;
  image: string | null;
  pros: string[];
  price_range: string | null;
  is_active: boolean;
  sort_order: number;
};

export type ProductPricing = {
  id: number;
  category_name: string;
  items: Array<{name: string; price_range: string}>;
  min_order: string | null;
  notes: string | null;
  is_active: boolean;
  sort_order: number;
};

export type Facility = {
  id: number;
  name: string;
  description: string;
  image: string | null;
  is_active: boolean;
  sort_order: number;
};

export type LandingPageData = {
  hero_slides: HeroSlide[];
  features: Feature[];
  testimonials: Testimonial[];
  materials: Material[];
  portfolio_items: PortfolioItem[];
  order_steps: OrderStep[];
  partners: Partner[];
  printing_methods: PrintingMethod[];
  product_pricings: ProductPricing[];
  facilities: Facility[];
  cta: CtaData;
  site_settings?: SiteSettings;
  categories: Category[];
  new_arrivals: Product[];
  best_sellers: Product[];
  team_members?: TeamMember[];
};


// Midtrans Snap Types
export type MidtransTransactionStatus = 'capture' | 'settlement' | 'pending' | 'deny' | 'cancel' | 'expire' | 'failure';

export type MidtransPaymentResponse = {
  status_code: string;
  status_message: string;
  transaction_id: string;
  order_id: string;
  gross_amount: string;
  payment_type: string;
  transaction_time: string;
  transaction_status: MidtransTransactionStatus;
  fraud_status?: string;
  redirect_url?: string;
};

export type MidtransSnapCallbacks = {
  onSuccess: (result: MidtransPaymentResponse) => void;
  onPending: (result: MidtransPaymentResponse) => void;
  onError: (result: MidtransPaymentResponse) => void;
  onClose: () => void;
};

export type MidtransSnap = {
  pay: (token: string, callbacks: MidtransSnapCallbacks) => void;
};

// API Response Types
export type ApiErrorResponse = {
  error: string;
  message?: string;
  status?: number;
  details?: Record<string, string[]>;
};

export type CheckoutResponse = {
  snapToken?: string;
  orderId?: string;
  orderNumber?: string;
  redirectUrl?: string;
  mock?: boolean;
  error?: string;
};

export type CheckoutPayload = {
  cart_id: string;
  shipping_address: {
    recipient_name: string;
    phone: string;
    address_line_1: string;
    address_line_2?: string;
    city: string;
    province: string;
    postal_code: string;
    country?: string;
  };
  shipping_method?: string;
  payment_method?: string;
  notes?: string;
};

// Pricing Types
export type PlastisolPrice = {
  title: string;
  price?: string;
  short?: string;
  long?: string;
  popular?: boolean;
};

export type PricingAddon = {
  name: string;
  price: string;
};

// Generic API Error for catch blocks
export type UnknownError = Error & {
  status?: number;
  response?: {
    data?: ApiErrorResponse;
  status?: number;
  };
};
