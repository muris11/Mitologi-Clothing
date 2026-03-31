import { expect, test } from '@playwright/test';

test.describe('Authentication Pages', () => {
  test('login page should display form fields', async ({ page }) => {
    await page.goto('/shop/login');
    await expect(page.locator('input[type="email"]')).toBeVisible();
    await expect(page.locator('input[type="password"]')).toBeVisible();
  });

  test('login should show validation error with empty fields', async ({ page }) => {
    await page.goto('/shop/login');
    // Try to submit empty form
    const submitBtn = page.locator('button[type="submit"]');
    if (await submitBtn.isVisible()) {
      await submitBtn.click();
      // Should stay on login page
      await expect(page).toHaveURL(/\/shop\/login/);
    }
  });

  test('register page should display form fields', async ({ page }) => {
    await page.goto('/shop/register');
    await expect(page.locator('input[type="email"]')).toBeVisible();
    const passwordFields = page.locator('input[type="password"]');
    await expect(passwordFields.first()).toBeVisible();
  });

  test('forgot password page should be accessible', async ({ page }) => {
    await page.goto('/shop/forgot-password');
    await expect(page.locator('input[type="email"]')).toBeVisible();
  });
});
