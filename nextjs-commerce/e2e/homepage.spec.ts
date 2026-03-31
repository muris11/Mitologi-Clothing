import { expect, test } from '@playwright/test';

test.describe('Homepage', () => {
  test('should load the landing page successfully', async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveTitle(/Mitologi/i);
  });

  test('should display the navigation bar', async ({ page }) => {
    await page.goto('/');
    const nav = page.locator('nav');
    await expect(nav.first()).toBeVisible();
  });

  test('should navigate to shop page', async ({ page }) => {
    await page.goto('/');
    await page.click('a[href="/shop"]');
    await expect(page).toHaveURL(/\/shop/);
  });

  test('should navigate to contact page', async ({ page }) => {
    await page.goto('/kontak');
    await expect(page.locator('text=Kontak')).toBeVisible();
  });

  test('should have skip-to-content accessibility link', async ({ page }) => {
    await page.goto('/');
    const skipLink = page.locator('a[href="#main-content"]');
    await expect(skipLink).toBeAttached();
  });
});
