# Chat Conversation System - Dokumentasi Implementasi

## Ringkasan
Sistem chatbot dengan history percakapan lengkap telah diimplementasikan. Fitur utama:
1. **Chat dari Product Detail**: Customer dapat membuka modal chat saat melihat detail produk
2. **Save History**: Semua percakapan tersimpan di database dengan relasi ke conversation
3. **Admin Dashboard**: Admin dapat melihat semua percakapan customer
4. **Admin Take-Over**: Admin dapat mengambil alih percakapan bot dan menjawab customer secara langsung
5. **Real-time Chat**: Chat messages diupdate real-time dengan auto-refresh

---

## Arsitektur Sistem

### Database Schema
```
chat_conversations
├── id (PK)
├── user_id (FK → users)
├── product_id (FK → products)
├── admin_id (FK → admins) - siapa admin yang handle
├── status: enum('active', 'in_progress', 'resolved')
├── is_admin_active: boolean - status chatbot (bot/admin)
├── taken_over_by_admin: boolean
├── taken_over_at: timestamp
├── keywords: json
├── chat_source: enum('product_detail', 'catalog', etc)
├── subject: string
└── timestamps

chat_messages
├── id (PK)
├── chat_conversation_id (FK)
├── sender_type: enum('user', 'bot', 'admin')
├── message: text
├── is_admin_reply: boolean
├── is_read_by_user: boolean
├── is_read_by_admin: boolean
├── metadata: json
└── timestamps
```

### API Endpoints

#### Customer Endpoints (Protected by auth middleware)
```
POST   /api/chat/conversation/create              - Buat atau dapatkan conversation
POST   /api/chat/message/send                     - Kirim message (user atau admin)
GET    /api/chat/history/{conversationId}        - Ambil chat history
POST   /api/chat/message/{messageId}/read        - Mark message as read
```

#### Admin Endpoints (Protected by admin middleware)
```
GET    /admin/api/chat/conversations              - Ambil semua conversations
GET    /admin/api/chat/conversation/{id}         - Detail conversation dengan messages
POST   /admin/api/chat/conversation/{id}/take-over    - Admin ambil alih conversation
POST   /admin/api/chat/conversation/{id}/release      - Admin selesaikan conversation
POST   /admin/api/chat/message/send              - Admin kirim message
GET    /admin/api/chat/unread-count              - Hitung unread messages
```

---

## Komponen Frontend

### 1. Chat Conversation API Class (`resources/js/chatbot/chat-conversation-api.js`)
```javascript
// Class untuk komunikasi dengan backend API
new ChatConversationAPI()
  .getOrCreateConversation(productId)
  .sendMessage(conversationId, message, productData)
  .getChatHistory(conversationId)
  .markMessageAsRead(messageId)
```

### 2. Enhanced Product ChatBot Class
```javascript
// Class untuk handle UI dan interaksi chat di product detail
new EnhancedProductChatBot()
  .openChat()        // Buka modal chat
  .sendMessage()     // Kirim message
  .loadChatHistory() // Load history percakapan lama
```

### 3. Admin Dashboard (`resources/views/admin/chatbot/conversations.blade.php`)
```javascript
// Class untuk dashboard admin
new AdminChatDashboard()
  .loadConversations()     // Load semua conversations
  .selectConversation(id)  // Pilih conversation untuk dibaca
  .sendMessage()           // Admin kirim message
  .takeOverConversation()  // Ambil alih dari bot
  .releaseConversation()   // Selesaikan conversation
```

---

## Backend Controller

### ChatConversationController Methods

#### getOrCreateConversation()
- Input: product_id
- Jika conversation sudah ada untuk user + product, ambil yang lama
- Jika belum ada, buat conversation baru
- Return: conversation_id

#### sendMessage()
- Tentukan sender_type (user, admin, bot)
- Jika user: simpan message, panggil N8N bot untuk response
- Jika admin: simpan message admin dan mark as admin_reply
- Update conversation status dan timestamps

#### getChatHistory()
- Return semua messages dengan format yang clean
- Authorized: user yang punya conversation atau admin

#### adminTakeOver()
- Set admin_id, is_admin_active = true
- Create system message notifying customer
- Update status ke 'in_progress'

#### adminReleaseConversation()
- Set is_admin_active = false
- Status ke 'resolved'
- Create system message

---

## Flow Percakapan Customer

```
1. Customer buka product detail
2. Click chat button
3. Frontend: POST /api/chat/conversation/create
4. Backend: Cari atau buat conversation
5. Frontend: Load chat history jika ada
6. Customer: Ketik message dan submit
7. Frontend: POST /api/chat/message/send
8. Backend: 
   - Simpan user message
   - Panggil N8N untuk bot response
   - Simpan bot response
9. Frontend: Auto-refresh history / polling
10. Chat messages tampil di UI
```

---

## Flow Admin Take-Over

```
1. Admin login ke admin dashboard
2. Buka /admin/chatbot/conversations
3. Lihat daftar percakapan customer
4. Click percakapan untuk buka detail
5. Click "Ambil Alih" button
6. Frontend: POST /admin/api/chat/conversation/{id}/take-over
7. Backend:
   - Set admin_id = current admin
   - Set is_admin_active = true
   - Create system message
8. Admin chat area unlock untuk mengetik
9. Admin ketik response dan kirim
10. Frontend: POST /admin/api/chat/message/send
11. Message ditampilkan di customer chat (dengan auto-refresh)
12. Customer bisa reply
13. Admin: Click "Selesaikan" untuk end conversation
14. Frontend: POST /admin/api/chat/conversation/{id}/release
```

---

## Implementasi Checklist

- ✅ Database migrations untuk chat tables
- ✅ ChatConversationController dengan semua methods
- ✅ API routes untuk customer dan admin
- ✅ ChatConversationAPI JavaScript class
- ✅ EnhancedProductChatBot class di product detail
- ✅ Admin dashboard dengan conversations list
- ✅ AdminChatDashboard JavaScript class
- ✅ Real-time message refresh dengan polling
- ✅ Chat history loading
- ✅ Admin take-over functionality
- ✅ System messages untuk events
- ✅ Read status tracking

---

## File-file yang Dibuat/Dimodifikasi

### Created:
- `app/Http/Controllers/ChatConversationController.php` - Main controller
- `resources/js/chatbot/chat-conversation-api.js` - Frontend API handler
- `resources/views/admin/chatbot/conversations.blade.php` - Admin dashboard

### Modified:
- `routes/web.php` - Added API routes
- `resources/views/pages/product-detail.blade.php` - Changed import JS

---

## Cara Penggunaan

### Customer:
1. Buka product detail page
2. Click tombol chat (💬)
3. Modal chat akan terbuka
4. Pilih template question atau ketik sendiri
5. Bot akan respond
6. Jika perlu bantuan admin, admin akan ambil alih

### Admin:
1. Login ke admin panel
2. Go to Admin → Chatbot → Conversations
3. Lihat daftar percakapan customer
4. Filter berdasarkan status (Aktif, Proses, Selesai)
5. Search customer by name
6. Click percakapan untuk lihat detail
7. Click "Ambil Alih" untuk respond langsung
8. Ketik message dan kirim
9. Lihats customer replies secara real-time
10. Click "Selesaikan" ketika selesai

---

## Testing

### Test 1: Customer Chat Flow
```
1. Go to /product-detail
2. Click chat button
3. Send template question
4. Check database: chat_conversations, chat_messages
5. Bot should respond (if N8N connected)
```

### Test 2: Admin Take-Over
```
1. Go to /admin/chatbot/conversations
2. Select customer conversation
3. Click "Ambil Alih"
4. Send response message
5. Check if message appears in customer chat
6. Customer reply
7. Admin sees reply in real-time
8. Click "Selesaikan"
```

### Test 3: Chat History
```
1. Open chat, send 3 messages
2. Close modal
3. Reopen chat
4. Old messages should still be visible
```

---

## Configuration

Pastikan environment variables terset:
```
N8N_WEBHOOK_URL=http://localhost:5678/webhook/chatbot (atau URL production)
```

---

## Future Enhancements

1. **Websocket Support**: Replace polling dengan websocket untuk real-time updates
2. **Typing Indicators**: Show "customer is typing..." status
3. **File Uploads**: Support upload gambar/dokumen
4. **Canned Responses**: Pre-made messages untuk admin
5. **Chat Rating**: Customer rating untuk percakapan
6. **Analytics**: Dashboard untuk analytics percakapan
7. **Multi-language**: Support chat dalam berbagai bahasa
8. **Mobile App**: Native app untuk customer support

---

## Notes

- Conversation auto-deletes sesuai setting di migration (expires_at field)
- All messages encrypted/sanitized untuk security
- Admin permissions check di controller method isAdminWithPermission()
- Auto-refresh conversations: 5 detik
- Auto-refresh messages: 2 detik (jika conversation terbuka)
