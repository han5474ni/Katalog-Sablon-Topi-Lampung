#!/bin/bash
# Fix untuk Hostinger Server - Resolve Merge Conflict

# Conflict di public/.htaccess
# Solusi: Accept incoming version (dari remote) yang sudah improved

cd ~/public_html

# Resolve conflict dengan menggunakan version dari remote (incoming)
git checkout --theirs public/.htaccess

# Stage the resolved file
git add public/.htaccess

# Complete the merge
git commit -m "Merge: Resolve public/.htaccess conflict by accepting incoming improved version"

# Verify
git log --oneline -3

echo ""
echo "✅ Merge conflict resolved successfully!"
echo "✅ Server is now up to date with latest changes"
