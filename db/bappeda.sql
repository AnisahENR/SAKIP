-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2021 at 05:23 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bappeda`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_golongan`
--

CREATE TABLE `m_golongan` (
  `id` int(11) NOT NULL,
  `deskripsi_golongan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `m_jabatan`
--

CREATE TABLE `m_jabatan` (
  `id` int(11) NOT NULL,
  `deskripsi` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `m_perangkat_daerah`
--

CREATE TABLE `m_perangkat_daerah` (
  `id` int(11) NOT NULL,
  `nama_perangkat_daerah` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t_akun`
--

CREATE TABLE `t_akun` (
  `id_akun` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `id_perangkat_daerah` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t_pegawai`
--

CREATE TABLE `t_pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` int(50) NOT NULL,
  `id_perangkat_daerah` int(11) NOT NULL,
  `id_golongan` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `id_sungram` int(11) NOT NULL,
  `tugas` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t_program`
--

CREATE TABLE `t_program` (
  `id` int(11) NOT NULL,
  `program` varchar(500) NOT NULL,
  `anggaran` varchar(500) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `id_pegawai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `t_sasaran`
--

CREATE TABLE `t_sasaran` (
  `id` int(11) NOT NULL,
  `sasaran` varchar(225) NOT NULL,
  `indikator_kinerja` varchar(225) NOT NULL,
  `satuan` varchar(225) NOT NULL,
  `target` varchar(225) NOT NULL,
  `id_pegawai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_golongan`
--
ALTER TABLE `m_golongan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_jabatan`
--
ALTER TABLE `m_jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_perangkat_daerah`
--
ALTER TABLE `m_perangkat_daerah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_akun`
--
ALTER TABLE `t_akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indexes for table `t_pegawai`
--
ALTER TABLE `t_pegawai`
  ADD PRIMARY KEY (`id_pegawai`);

--
-- Indexes for table `t_program`
--
ALTER TABLE `t_program`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_sasaran`
--
ALTER TABLE `t_sasaran`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_golongan`
--
ALTER TABLE `m_golongan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_jabatan`
--
ALTER TABLE `m_jabatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_sasaran`
--
ALTER TABLE `t_sasaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
