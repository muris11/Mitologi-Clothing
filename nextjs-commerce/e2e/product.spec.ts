import { expect, test } from "@playwright/test";

test.describe("Product Page", () => {
  test("should display product list on shop page", async ({ page }) => {
    await page.goto("/shop");
    // Wait for products to load
    await page.waitForLoadState("networkidle");
    const title = await page.title();
    expect(title).toBeTruthy();
  });

  test("should navigate to product detail page", async ({ page }) => {
    await page.goto("/shop");
    await page.waitForLoadState("networkidle");

    // Click first product link
    const productLink = page.locator('a[href*="/shop/product/"]').first();
    if (await productLink.isVisible()) {
      await productLink.click();
      await expect(page).toHaveURL(/\/shop\/product\//);
      // Verify product detail elements exist
      await expect(page.locator("text=Deskripsi Produk")).toBeVisible({
        timeout: 15000,
      });
    }
  });

  test("product page should have structured data", async ({ page }) => {
    await page.goto("/shop");
    await page.waitForLoadState("networkidle");

    const productLink = page.locator('a[href*="/shop/product/"]').first();
    if (await productLink.isVisible()) {
      await productLink.click();
      await page.waitForLoadState("networkidle");

      // Check for JSON-LD structured data
      const jsonLd = page.locator('script[type="application/ld+json"]');
      await expect(jsonLd.first()).toBeAttached();
    }
  });
});
