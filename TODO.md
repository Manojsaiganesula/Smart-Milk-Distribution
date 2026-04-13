# Milk Distribution Management System TODO - Status Update

## Current Status
- ✅ MySQL running on 3306
- ❌ Apache not running on 80 (start needed)
- ❌ No symlink yet

## Quick Fix Steps
1. **Start Apache:** Run `C:\xampp\apache_start.bat`
2. **Create symlink (CMD as Admin):** 
   ```
   cd /d C:\xampp\htdocs
   rmdir milk-system
   mklink /D milk-system C:\Users\ganes\Desktop\milk-distribution-system
   ```
3. **DB:** http://localhost/phpmyadmin → Import db.sql to milk_db
4. **Test:** http://localhost/milk-system/

### [x] All Code Complete
### Next: Run above commands



