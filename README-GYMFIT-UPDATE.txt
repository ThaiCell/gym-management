GYMFIT - BAN CAP NHAT GIAO DIEN VA CHUC NANG
================================================

Cac thay doi trong ban nay:
1. Dong bo mau do-den trong cac layout user, dashboard hoi vien va admin.
2. Chuan hoa mau nen, thanh menu, trang thai active/hover, the va nut.
3. Thay emoji/icon ky hieu trang tri trong cac Blade view bang SVG icon dong bo.
4. Sua nhan dien trang thai hoa don admin bang chuan hoa chuoi (bo dau, chuan hoa khoang trang):
   - Da thanh toan: xanh la
   - Cho thanh toan: vang
   - Da huy: do
5. Hoan thien xu ly lien he trong Admin:
   - GET /admin/contacts: danh sach lien he, chi role_id=1.
   - POST /admin/contacts/{id}/update: cap nhat trang thai va phan hoi.
   - Giu nguyen luong xu ly lien he cua nhan vien.
6. Dieu chinh nut huy goi PT tren dashboard hoi vien: chi hien khi hoa don dang cho thanh toan va con trong 72 gio, phu hop dieu kien xu ly trong controller.
7. Hien thi "Cho thanh toan" cho dang ky PT dang doi hoa don, khong hien "Khong hoat dong" gay nham lan.

CAI DAT
-------
1. Sao luu source hien tai va database truoc khi thay.
2. Giai nen ZIP vao thu muc du an Laravel, ghi de cac file.
3. GIU file .env hien tai; goi ZIP khong dong goi .env va .git.
4. Chay:
   composer dump-autoload
   php artisan optimize:clear
5. Kiem tra:
   php artisan route:list
6. Mo lai trang bang Ctrl+F5.

KHONG chay php artisan migrate:fresh.
Khong co migration moi trong ban cap nhat nay.
Chua kiem thu tren database MySQL cua may nguoi dung; can test dang nhap, admin lien he,
huy goi PT va trang thanh toan tren du lieu thuc te.
