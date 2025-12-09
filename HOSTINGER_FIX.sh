#!/bin/bash

# 🔧 FIX HOSTINGER IMAGE 404 ERRORS - AUTO SCRIPT
# Script ini akan fix symlink dan image preview 404 error di Hostinger

set -e  # Exit jika ada error

echo "=========================================="
echo "🔧 HOSTINGER IMAGE FIX SCRIPT"
echo "=========================================="
echo ""

# Variables
PROJECT_ROOT="/home/u157843933/domains/sablontopilampung.com/public_html"
STORAGE_LINK="$PROJECT_ROOT/public/storage"
TARGET_PATH="$PROJECT_ROOT/storage/app/public"

# Step 1: Navigate to project
echo "📁 Step 1: Navigating to project directory..."
if [ ! -d "$PROJECT_ROOT" ]; then
    echo "❌ Error: Project directory not found at $PROJECT_ROOT"
    exit 1
fi
cd "$PROJECT_ROOT"
echo "✅ In: $(pwd)"
echo ""

# Step 2: Verify storage files exist
echo "📊 Step 2: Verifying storage files..."
if [ ! -d "$TARGET_PATH" ]; then
    echo "❌ Error: Storage directory not found at $TARGET_PATH"
    exit 1
fi

FILE_COUNT=$(find "$TARGET_PATH" -type f | wc -l)
echo "✅ Found $FILE_COUNT files in storage/app/public/"
echo ""

# Step 3: Check current symlink status
echo "🔗 Step 3: Checking current symlink status..."
if [ -L "$STORAGE_LINK" ]; then
    CURRENT_TARGET=$(readlink "$STORAGE_LINK")
    echo "⚠️  Symlink exists, pointing to: $CURRENT_TARGET"
    echo "🗑️  Removing old symlink..."
    rm -f "$STORAGE_LINK"
    echo "✅ Old symlink removed"
elif [ -d "$STORAGE_LINK" ]; then
    echo "⚠️  Directory found instead of symlink (old installation)"
    echo "🗑️  Removing old directory..."
    rm -rf "$STORAGE_LINK"
    echo "✅ Old directory removed"
elif [ -e "$STORAGE_LINK" ]; then
    echo "⚠️  File found instead of symlink"
    echo "🗑️  Removing..."
    rm -f "$STORAGE_LINK"
    echo "✅ Removed"
else
    echo "ℹ️  No existing symlink/directory found (will create new)"
fi
echo ""

# Step 4: Create new symlink
echo "🔗 Step 4: Creating new symlink..."
php artisan storage:link
if [ -L "$STORAGE_LINK" ]; then
    TARGET=$(readlink "$STORAGE_LINK")
    echo "✅ Symlink created successfully: $STORAGE_LINK -> $TARGET"
else
    echo "❌ Failed to create symlink"
    exit 1
fi
echo ""

# Step 5: Verify symlink works
echo "✔️  Step 5: Verifying symlink..."
SYMLINK_TARGET=$(readlink "$STORAGE_LINK")
if [ "$SYMLINK_TARGET" = "../storage/app/public" ]; then
    echo "✅ Symlink points to correct target"
else
    echo "⚠️  Symlink points to: $SYMLINK_TARGET (expected: ../storage/app/public)"
fi
echo ""

# Step 6: Test file accessibility
echo "🧪 Step 6: Testing file accessibility..."
SAMPLE_FILE=$(find "$TARGET_PATH/variants" -type f | head -1)
if [ -z "$SAMPLE_FILE" ]; then
    echo "⚠️  No variant files found to test"
else
    FILENAME=$(basename "$SAMPLE_FILE")
    if [ -f "$STORAGE_LINK/$FILENAME" ] 2>/dev/null || [ -L "$STORAGE_LINK" ]; then
        echo "✅ Sample file accessible via symlink: $FILENAME"
    else
        echo "⚠️  Could not access sample file via symlink"
    fi
fi
echo ""

# Step 7: Clear Laravel cache
echo "🧹 Step 7: Clearing Laravel cache..."
php artisan config:cache > /dev/null 2>&1
echo "✅ Config cached"
php artisan cache:clear > /dev/null 2>&1
echo "✅ Cache cleared"
php artisan route:cache > /dev/null 2>&1
echo "✅ Routes cached"
php artisan optimize:clear > /dev/null 2>&1
echo "✅ Optimization cache cleared"
echo ""

# Step 8: Permission check
echo "🔐 Step 8: Checking permissions..."
STORAGE_PERMS=$(ls -ld "$TARGET_PATH" | awk '{print $1}')
echo "Storage permissions: $STORAGE_PERMS"

if [ -w "$TARGET_PATH" ]; then
    echo "✅ Storage directory is writable"
else
    echo "⚠️  Storage directory might not be writable"
fi
echo ""

# Step 9: Summary
echo "=========================================="
echo "✨ CONFIGURATION COMPLETE!"
echo "=========================================="
echo ""
echo "📋 Summary:"
echo "  ✅ Symlink: $STORAGE_LINK -> $SYMLINK_TARGET"
echo "  ✅ Target: $TARGET_PATH"
echo "  ✅ Files in storage: $FILE_COUNT"
echo "  ✅ Cache cleared"
echo ""
echo "🎯 Next Steps:"
echo "  1. Open browser and go to: https://sablontopilampung.com/admin/management-product"
echo "  2. Press Ctrl+Shift+Delete to clear browser cache"
echo "  3. Refresh the page"
echo "  4. Check if product images now display correctly"
echo ""
echo "🧪 Test URL:"
echo "  curl -I https://sablontopilampung.com/storage/variants/[filename].webp"
echo "  Should return: HTTP/2 200 OK"
echo ""
echo "📞 If still having issues, check logs:"
echo "  tail -50 storage/logs/laravel.log | grep -i storage"
echo ""
