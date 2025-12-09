#!/bin/bash
# Complete script untuk resolve merge conflict di Hostinger

cd ~/public_html

echo "🔍 Checking git status..."
git status

echo ""
echo "📋 Files in conflict:"
git diff --name-only --diff-filter=U

echo ""
echo "✅ Resolving conflict by accepting remote version..."

# Accept remote (incoming) version
git checkout --theirs public/.htaccess

echo ""
echo "📝 Staging resolved file..."
git add public/.htaccess

echo ""
echo "💾 Completing merge..."
git commit -m "Merge: Resolve public/.htaccess conflict - accept improved version from remote"

echo ""
echo "✅ Merge completed successfully!"
echo ""
echo "📊 Recent commits:"
git log --oneline -3

echo ""
echo "🎉 All done! Server is now up to date."
