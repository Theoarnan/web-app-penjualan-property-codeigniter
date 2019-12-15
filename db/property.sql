DROP DATABASE propertydb;
CREATE DATABASE propertydb;
USE propertydb;

CREATE TABLE admin(
    id_admin int not null primary key auto_increment,
    username_admin varchar(50),
    password_admin varchar(200),
    token_admin varchar(200),
    token_expired_admin date,
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

insert into admin(username_admin,password_admin,token_admin,token_expired_admin)
value ("arnan","$2y$10$PJNOmdtcvJMImRH9CHcKUOqS7sf7NVRK1co4aiqnrYcODJITkRI3u",md5('as'),"2019-10-25");

CREATE TABLE customer(
    id_customer int not null primary key auto_increment,
    nama_customer varchar(50),
    alamat_customer varchar(100),
    jk_customer enum("L","P"),
    telp_customer int(20),
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

CREATE TABLE tipe_property(
    id_tipe int not null primary key auto_increment,
    tipe varchar(50),
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

CREATE TABLE property(
    id_property int not null primary key auto_increment,
    nama_property varchar(100),
    harga_property int,
    stock_property int,
    detail_property varchar(1000),
    lokasi_property varchar(100),
    gambar_property varchar(200),
    tipe_property varchar(1000),
    -- foreign key property(tipe_property) references tipe_property(id_tipe),
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

CREATE TABLE bayar_transaksi(
    id_bayar_transaksi int not null primary key auto_increment,
    tgl_bayar date,
    jumlah_bayar int,
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

DROP TABLE transaksi_pesan;
CREATE TABLE transaksi_pesan(
    id_transaksipesan int not null primary key auto_increment,
    no_transaksipesan varchar(100),
    tgl_pesan_transaksipesan date,
    nomor int(4),
    -- jumlah_transaksipesan int,
    -- totalbayar_transaksipesan int,
    -- status_transaksipesan enum("Lunas","Belum Bayar"),
    -- customer_id int not null,
    -- admin_id int not null,
    -- property_id int not null,
    -- transaksibayar_id int not null,
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

DROP TABLE item_transaksi;
CREATE TABLE item_transaksi(
    id_item_transaksi int not null primary key auto_increment,
    customer_id int not null,
    -- admin_id int not null,
    property_id int not null,
    transaksipesan_id int not null,
    total_item_transaksi int,
    harga_item_transaksi int,
    -- transaksibayar_id int not null,
    created_at timestamp not null default now(),
    updated_at timestamp not null default now() on update now()
);

ALTER TABLE item_transaksi ADD FOREIGN KEY (customer_id) REFERENCES customer(id_customer);
-- ALTER TABLE item_transaksi ADD FOREIGN KEY (admin_id) REFERENCES admin(id_admin);
ALTER TABLE item_transaksi ADD FOREIGN KEY (property_id) REFERENCES property(id_property);
ALTER TABLE item_transaksi ADD FOREIGN KEY (transaksipesan_id) REFERENCES transaksi_pesan(id_transaksipesan);
-- ALTER TABLE transaksi_pesan ADD FOREIGN KEY (transaksibayar_id) REFERENCES bayar_transaksi(id_bayar_transaksi);
