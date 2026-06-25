# Linktr One customization notes

This branch adds the database, backend, and first public frontend foundation for the custom Linktr One build on top of LinkStack.

## Backend phase

- Added 40+ common Chinese and English font stacks.
- Added common preset icons for X, Telegram, Instagram, YouTube, cloud, payment, lock, and more.
- Added per-user categories such as Social Media and 社交媒体.
- Added per-user gradient background, fonts, colors, buttons, avatar radius, and footer text.
- Added per-user Open Graph, X Card, and Telegram preview settings.
- Extended links with category assignment, per-link icons, button colors, visibility, new-tab behavior, and nofollow behavior.
- Added models for categories, user styles, and share cards.
- Added LinktrOneController and backend route group.
- Added backend settings view at /studio/linktr-one.

## Frontend phase

- Added public Linktr One style override module.
- Public page now uses a centered avatar, name, bio, social icons, categorized sections, rounded buttons, left icon, right three-dot decoration, and non-clickable footer text.
- Link buttons render inside user-defined categories when categories exist.
- Each link can use an uploaded icon, a preset icon, or no icon.
- Per-link button background and text colors are applied on the public page.
- User-level gradient background, fonts, and colors are applied on the public page.
- Metadata now prefers user-level OG and X Card settings and falls back to profile information.
- Footer is user-configurable and non-clickable.
- Backend Linktr One settings page now includes link-level controls and a Telegram-card-style preview.

## Run after pulling this branch

php artisan migrate
php artisan storage:link
php artisan config:clear
php artisan route:clear
php artisan view:clear

## Next phase

Improve navigation from the existing LinkStack sidebar into /studio/linktr-one, refine public theme details, and add default seed categories for new accounts if needed.
