# Milk Distribution System

## Production QR Setup

1. Run ngrok: `ngrok http 8080` → Copy `https://xyz.ngrok-free.app`
2. Update QR img src:
   ```
   https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=https://xyz.ngrok-free.app/milk-system/
   ```
3. Print QR → Customers scan from any phone globally!

**Local Test:** http://localhost:8080/milk-system/
**Admin:** http://localhost:8080/milk-system/admin/login.php

## Features
- Cow/Buffalo milk addons
- 10AM-9PM update window
- Organic farm UI
- Auto DB setup

Live & perfect!

