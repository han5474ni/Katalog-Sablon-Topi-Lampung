#!/bin/bash
# Fix untuk Hostinger Server - Resolve Merge Conflict

# Conflict di public/.htaccess
# Solusi: Accept incoming version (dari remote) yang sudah improved

cd ~/public_html

echo "🔍 Checking git status..."
git status

echo ""
echo "📋 Files in conflict:"
git diff --name-only --diff-filter=U

echo ""
echo "✅ Resolving conflict by accepting remote version..."

# Resolve conflict dengan menggunakan version dari remote (incoming)
git checkout --theirs public/.htaccess

# Stage the resolved file
git add public/.htaccess

echo ""
echo "💾 Completing merge..."
git commit -m "Merge: Resolve public/.htaccess conflict by accepting incoming improved version"

echo ""
echo "✅ Merge conflict resolved successfully!"

echo ""
echo "📊 Recent commits:"
git log --oneline -3

echo ""
echo "🎉 All done! Server is now up to date."
echo "✅ You can now continue with your next git pull if needed"
