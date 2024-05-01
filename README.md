## UAS Pemrograman Web Gasal 2023/2024

## Kelas B Kelompok 8

## Anggota Kelompok:
    - I Komang Agus Tinar Virgo Triyana Putra (210711236)
    - Gideon Bahtera Adya Pratama (210711250)
    - Yerico Elwido (210711116)

## Username & Password Login:
    - Login User:
        - Username:
        - Password:
    - Login Admin:
        - Email: admin@gmail.com
        - Password: 12345678

## Bonus yang diambil:
    - Hosting:
        - Backend: https://laundryspace.online
        - Frontend: 
        
    - Routes API:
        # Auth
        - GET /api
        - GET /api/users/{id} - Get User With Specified ID user
        - POST /api/register - Register
        - POST /api/login - Login
        
        # User
        - GET /api/users - Get All User
        - GET /api/userLogin - Get Current User Logged in
        - POST /api/users/update - Update Current User Logged in
        - POST /api/usersUpdate/{id} - Update User (Admin)
        - DELETE /users/{id} - Delete User
        
        # Item
        - GET /api/item - Get All Item
        - GET /api/item/{id} - Get Specified Item by ID item
        - POST /api/item - Create Item
        - PUT /api/item/{id} - Update Item
        - DELETE /item/{id} - Delete Item
        
        # Layanan
        - GET /api/layanan - Get All Layanan
        - GET /api/layanan/{id} - Get Specified Layanan by ID layanan 
        - POST /api/layanan - Create Layanan
        - PUT /api/layanan/{id} - Update Layanan
        - DELETE /layanan/{id} - Delete Layanan
        
        # Jenis Pengambilan
        - GET /api/jenisPengambilan - Get All Jenis Pengambilan
        - GET /api/jenisPengambilan/{id} - Get Specified Jenis Pengambilan by ID jenis pengambilan 
        - POST /api/jenisPengambilan - Create Jenis Pengambilan
        - PUT /api/jenisPengambilan/{id} - Update Jenis Pengambilan
        - DELETE /jenisPengambilan/{id} - Delete Jenis Pengambilan
        
        # Deposit
        - GET /api/deposit - Get All Deposit
        - GET /api/deposit/{id} - Get Specified Deposit by ID deposit 
        - GET /api/depositUser/{id} - Get Specified Deposit User by ID deposit 
        - POST /api/deposit - Create Deposit
        - POST /api/pembayaran - Create Depost
        
        # Transaksi Laundry
        - GET /api/transaksiLaundry - Get All transaksiLaundry
        - GET /api/transaksiLaundry/{id} - Get Specified transaksiLaundry by ID transaksiLaundry 
        - GET /api/transaksiLaundryByUser - Get All transaksiLaundry by User
        - POST /api/transaksiLaundry - Create TransaksiLaundry
        - PUT /api/transaksiLaundry/harga/{id} - Update Harga Transaksi Laundry
        - PUT /api/transaksiLaundry/{id} - Update Transaksi Laundry
        - DELETE /api/transaksiLaundry/{id} - Delete Transasksi Laundry by ID
        - PUT /api/transaksiLaundry/bayar/{id} - Update Pembayaran Transaksi Laundry
        
        # Transaksi Tambahan
        - GET /api/transaksiTambahan/transaksi/{id} - Get All TranasaksiTambahan
        - GET /api/transaksiTambahan/{id} - Get All TranasaksiTambahan
        - POST /api/transaksiTambahan - Create Transaksi Tambahan
        - PUT /api/transaksiTambahan/{id} - Update Transaksi Tambahan
        - DELETE /transaksiTambahan/{id} - Delete Transaksi Tambahan
        
        ## React
        - Link Repository: https://github.com/SaunOne/PW2023_B_8_React.git
