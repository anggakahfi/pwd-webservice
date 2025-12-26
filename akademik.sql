-- Create mahasiswa table
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jkel VARCHAR(35) NOT NULL,
    alamat TEXT,
    tgllhr DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO mahasiswa (nim, nama, jkel, alamat, tgllhr) VALUES
('230018123', 'Ahmad Fauzi', 'Laki-laki', 'Jl. Merdeka No. 10, Jakarta', '2003-05-15'),
('230018052', 'Siti Nurhaliza', 'Perempuan', 'Jl. Sudirman No. 25, Bandung', '2003-08-22'),
('230018053', 'Budi Santoso', 'Laki-laki', 'Jl. Gatot Subroto No. 5, Surabaya', '2002-12-10'),
('230018054', 'Dewi Lestari', 'Perempuan', 'Jl. Ahmad Yani No. 15, Yogyakarta', '2003-03-18'),
('230018055', 'Eko Prasetyo', 'Laki-laki', 'Jl. Diponegoro No. 30, Semarang', '2003-07-05');

