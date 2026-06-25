# Linktr One backend phase

This branch adds the database and backend foundation for the custom Linktr One build on top of LinkStack.

## Added

- `config/linktr_fonts.php` with 40+ common Chinese and English font stacks.
- `linktr_categories` table for per-user categories such as `Social Media | 社交媒体`.
- `linktr_user_styles` table for per-user gradient background, fonts, colors, buttons, avatar radius, and footer text.
- `linktr_share_cards` table for per-user Open Graph, X/Twitter Card, and Telegram preview settings.
- Extra `links` table fields for category assignment, per-link icons, button colors, visibility, new-tab behavior, and nofollow behavior.
- Eloquent models for `LinktrCategory`, `LinktrUserStyle`, and `LinktrShareCard`.
- `LinktrOneController` with backend handlers for style, category, share-card, and link enhancement settings.
- `routes/linktr_one.php` and route registration in `routes/web.php`.
- First backend settings view at `/studio/linktr-one`.

## Run after pulling this branch

```bash
php artisan migrate
php artisan storage:link
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Next phase

The next phase should connect these new backend settings to the public Linktr One theme and the existing link edit/list screens.
