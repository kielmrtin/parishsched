const { chromium } = require('playwright');
(async () => {
  const browser = await chromium.launch({ args: ['--no-sandbox'] });
  const page = await browser.newPage({ viewport: { width: 1400, height: 1100 }, deviceScaleFactor: 4 });
  await page.goto('http://127.0.0.1:8123/admin');
  await page.fill('input[name="username"]', 'admin');
  await page.fill('input[name="password"]', 'admin');
  await Promise.all([page.waitForNavigation(), page.click('button[type="submit"]')]);
  await page.waitForTimeout(300);
  const closeBtn = page.locator('#ps-overlay-adm-welcome .ps-btn-solo');
  if (await closeBtn.count()) await closeBtn.click();
  await page.goto('http://127.0.0.1:8123/admin?section=schedule');
  await page.waitForSelector('#admin-schedule-calendar .calendar_day', { timeout: 8000 });

  // day-card pending pill
  const pendingDay = page.locator('#admin-schedule-calendar .calendar_day.status_pending').first();
  await pendingDay.click();
  await page.waitForTimeout(300);
  const pill = page.locator('#admin-calendar-details .status-pill.spill-pending').first();
  await pill.screenshot({ path: '/tmp/claude-1000/-home-snoopi-church-web/216d599b-2103-47a7-9917-0d802774c41d/scratchpad/pw/daycard_pending_recheck.png' });

  // rdm-modal pending badge - pause animation for a clean shot
  await page.locator('#admin-calendar-details .admin-detail-view-btn').first().click();
  await page.waitForTimeout(200);
  await page.evaluate(() => {
    document.querySelectorAll('.rdm-pulse-dot, .spill-dot').forEach(el => { el.style.animationPlayState = 'paused'; el.style.animation = 'none'; });
  });
  await page.waitForTimeout(100);
  await page.locator('#rdm-badges .rdm-badge-pending').first().screenshot({ path: '/tmp/claude-1000/-home-snoopi-church-web/216d599b-2103-47a7-9917-0d802774c41d/scratchpad/pw/rdm_pending_recheck.png' });

  await browser.close();
})();
