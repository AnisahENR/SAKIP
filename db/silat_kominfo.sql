-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 26, 2021 at 02:33 AM
-- Server version: 10.5.6-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `silat_kominfo3`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete sertifikasi` (IN `param_id` INT)  BEGIN
	DELETE FROM `trx_thl_sertfikat`
	WHERE id = param_id;
	
    SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_pekerjaan` (IN `param_id` INT, IN `param_kode` VARCHAR(165))  BEGIN
	DELETE FROM `trx_thl_pengalaman_kerja`
	WHERE id = param_id
    AND kode_thl = param_kode;
	
    SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_sertifikasi` (IN `param_id` INT, IN `param_kode` VARCHAR(165))  BEGIN
	DELETE FROM `trx_thl_sertfikat`
	WHERE id = param_id
    AND kode_thl = param_kode;
	
    SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_aktivitas_bulanan_thl` (IN `param_kode` VARCHAR(165), IN `param_month` DATE)  BEGIN
	SET @next_month = DATE_ADD(param_month, INTERVAL 1 MONTH);

	WITH
	laporan_thl(`laporan_id`,`tgl_laporan`,`kode_laporan_thl`)
	AS
	(
		SELECT `t_laporan_thl`.`id`                        	AS `laporan_id`,
				cast(`t_laporan_thl`.`tgl_laporan` AS date) AS `tgl_laporan`,
				`t_laporan_thl`.`kode_laporan_thl`			AS `kode_laporan_thl`
		FROM `t_laporan_thl`
		WHERE 	param_month <= `t_laporan_thl`.`tgl_laporan` AND `t_laporan_thl`.`tgl_laporan` < @next_month
			AND `t_laporan_thl`.`kode_thl` = param_kode
	),
	summary(`laporan_id`,`tgl_laporan`,`status_id`,`aktivitas`,`order`)
	AS
	(
		SELECT    laporan_thl.laporan_id						AS `laporan_id`,
				  cast(laporan_thl.tgl_laporan AS date) 		AS `tgl_laporan`,
				  `t_laporan_kegiatan_thl`.`stat_laporan_id`  	AS `status_id`,
				  CONCAT('(', `m_kegiatan_thl`.`deskripsi`, ') ', `t_laporan_kegiatan_thl`.`uraian`) AS `aktivitas`,
                  0												AS `order`
		FROM      laporan_thl
		INNER JOIN `t_laporan_kegiatan_thl`
		ON        laporan_thl.kode_laporan_thl = `t_laporan_kegiatan_thl`.`kode_laporan_thl`
		INNER JOIN `m_kegiatan_thl`
		ON        `m_kegiatan_thl`.`id` = `t_laporan_kegiatan_thl`.`kegiatan_thl_id`
        WHERE 	  `t_laporan_kegiatan_thl`.`stat_laporan_id` = 3
				
		UNION ALL
		SELECT    laporan_thl.laporan_id								AS `laporan_id`,
				  cast(laporan_thl.`tgl_laporan` AS date) 				AS `tgl_laporan`,
				  `t_laporan_lain_thl`.`stat_laporan_id`  				AS `status_id`,
				  CONCAT('(Lain) ', `t_laporan_lain_thl`.`uraian`)  	AS `aktivitas`,
                  1														AS `order`
		FROM      laporan_thl
		INNER JOIN `t_laporan_lain_thl`
		ON        laporan_thl.kode_laporan_thl = `t_laporan_lain_thl`.`kode_laporan_thl`
        WHERE 	  `t_laporan_lain_thl`.`stat_laporan_id` = 3
	)

	SELECT 	summary.laporan_id			AS laporan_id,
			summary.tgl_laporan			AS tanggal_laporan,
			summary.aktivitas			AS aktivitas,
			m_stat_laporan.deskripsi	AS status_aktivitas
	FROM summary
	INNER JOIN m_stat_laporan
	ON summary.status_id = m_stat_laporan.id
    ORDER BY summary.tgl_laporan, summary.order;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_avail_tgllaporan_thl` (IN `param_kode_thl` VARCHAR(16))  NO SQL
BEGIN
  SELECT
kode_target_thl,
tgl_laporan
FROM
t_target_thl
WHERE
stat_laporan_id = 3
AND kode_thl = param_kode_thl
AND kode_target_thl NOT IN (
SELECT
kode_target_thl
FROM
t_laporan_thl
WHERE
kode_thl = param_kode_thl);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_current_month` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  NO SQL
BEGIN
	SET @curr_date 	= CURDATE();
	SET @curr_month	= CONCAT(YEAR(@curr_date), '-', MONTH(@curr_date), '-01');
	SET @next_month = DATE_ADD(@curr_month, INTERVAL 1 MONTH);
	
	IF param_author = 1 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id							AS status_laporan_id, 
					COUNT(t_laporan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
			GROUP BY t_laporan_thl.stat_laporan_id
		)
		SELECT  status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
	
	ELSEIF param_author = 2 AND param_kode IS NOT NULL THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id							AS status_laporan_id, 
					COUNT(t_laporan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND	kode_spv = param_kode
			GROUP BY t_laporan_thl.stat_laporan_id
		)
		SELECT  status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
	
	ELSEIF param_author = 3 AND param_kode IS NOT NULL THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id							AS status_laporan_id, 
					COUNT(t_laporan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND	kode_thl = param_kode
			GROUP BY t_laporan_thl.stat_laporan_id
		)
		SELECT  status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
            
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_rekap_jumlah_thl` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
    
	IF param_author = 1 THEN
		SELECT COUNT(id) AS count_thl
		FROM t_thl;
	ELSEIF param_author = 2 THEN
		SELECT COUNT(id) AS count_thl
		FROM t_thl
        WHERE skpd_id = @skpd;
	ELSEIF param_author = 3 THEN
		SELECT COUNT(id) AS count_thl
		FROM t_thl
        WHERE skpd_id = @skpd;
	ELSEIF param_author = 4 THEN
		SELECT COUNT(id) AS count_thl
		FROM t_thl
        WHERE bidang_skpd_id = @bidang;
	ELSEIF param_author = 5 THEN
		SELECT COUNT(id) AS count_thl
		FROM t_thl
        WHERE kode_spv_kasie = param_kode;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_rekap_jumlah_thl_new` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
    
	IF param_author = 1 THEN
		WITH thl_count (skpd_id, thl_count)
		AS
		(
			SELECT skpd_id, COUNT(skpd_id) AS thl_count
			FROM t_thl
			GROUP BY skpd_id
		)
		SELECT 	deskripsi				AS skpd,
				COALESCE(thl_count, 0)	AS thl_count
		FROM m_skpd
		LEFT JOIN thl_count
			ON m_skpd.id = thl_count.skpd_id;
            
	ELSEIF param_author = 2 THEN
		WITH thl_count (bidang_skpd_id, thl_count)
		AS
		(
			SELECT bidang_skpd_id, COUNT(bidang_skpd_id) AS thl_count
			FROM t_thl
			WHERE skpd_id = @skpd
			GROUP BY bidang_skpd_id
		)
		SELECT 	deskripsi				AS bidang_skpd,
				COALESCE(thl_count, 0)	AS thl_count
		FROM m_bidang_skpd
		LEFT JOIN thl_count
			ON m_bidang_skpd.id = thl_count.bidang_skpd_id
		WHERE m_bidang_skpd.skpd_id = @skpd;
	
	ELSEIF param_author = 3 THEN
		WITH thl_count (bidang_skpd_id, thl_count)
		AS
		(
			SELECT bidang_skpd_id, COUNT(bidang_skpd_id) AS thl_count
			FROM t_thl
			WHERE skpd_id = @skpd
			GROUP BY bidang_skpd_id
		)
		SELECT 	deskripsi				AS bidang_skpd,
				COALESCE(thl_count, 0)	AS thl_count
		FROM m_bidang_skpd
		LEFT JOIN thl_count
			ON m_bidang_skpd.id = thl_count.bidang_skpd_id
		WHERE m_bidang_skpd.skpd_id = @skpd;
            
	ELSEIF param_author = 4 THEN
		WITH thl_count (kode_spv_kasie, thl_count)
		AS
		(
			SELECT kode_spv_kasie, COUNT(kode_spv_kasie) AS thl_count
			FROM t_thl
			WHERE bidang_skpd_id = @bidang
			GROUP BY kode_spv_kasie
		)
		
		SELECT 	t_spv.nama	AS nama_kasie,
				thl_count	AS thl_count
		FROM t_spv
        INNER JOIN t_akun
			ON t_akun.kode = t_spv.kode_spv
		LEFT JOIN thl_count
			ON t_spv.kode_spv = thl_count.kode_spv_kasie
		WHERE 	t_spv.bidang_skpd_id = @bidang
			AND	t_akun.author_id = 5;
	
	ELSEIF param_author = 5 AND param_kode IS NOT NULL THEN
		WITH thl_count (kode_spv_kasie, thl_count)
		AS
		(
			SELECT 	kode_spv_kasie	AS kode_spv_kasie,
					COUNT(id) 		AS thl_count
			FROM t_thl
			WHERE kode_spv_kasie = param_kode
			GROUP BY kode_spv_kasie
		)
		SELECT 	t_spv.nama	AS nama_kasie,
				thl_count	AS thl_count
		FROM t_spv
		INNER JOIN thl_count
			ON t_spv.kode_spv = thl_count.kode_spv_kasie;
	
	ELSE
		SELECT 1 AS result;
        
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_rekap_pendidikan_thl` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
    
	IF param_author = 1 THEN
		SELECT 	m_pendidikan.id			AS jenjang_id,
				m_pendidikan.deskripsi	AS jenjang,
				COUNT(m_pendidikan.id)	AS count_jenjang
		FROM t_thl
		INNER JOIN m_pendidikan
			ON t_thl.pendidikan_id = m_pendidikan.id
		GROUP BY m_pendidikan.id;
	ELSEIF param_author = 2 THEN
		SELECT 	m_pendidikan.id			AS jenjang_id,
				m_pendidikan.deskripsi	AS jenjang,
				COUNT(m_pendidikan.id)	AS count_jenjang
		FROM t_thl
		INNER JOIN m_pendidikan
			ON t_thl.pendidikan_id = m_pendidikan.id
        WHERE skpd_id = @skpd
		GROUP BY m_pendidikan.id;
	ELSEIF param_author = 3 THEN
		SELECT 	m_pendidikan.id			AS jenjang_id,
				m_pendidikan.deskripsi	AS jenjang,
				COUNT(m_pendidikan.id)	AS count_jenjang
		FROM t_thl
		INNER JOIN m_pendidikan
			ON t_thl.pendidikan_id = m_pendidikan.id
        WHERE skpd_id = @skpd
		GROUP BY m_pendidikan.id;
	ELSEIF param_author = 4 THEN
		SELECT 	m_pendidikan.id			AS jenjang_id,
				m_pendidikan.deskripsi	AS jenjang,
				COUNT(m_pendidikan.id)	AS count_jenjang
		FROM t_thl
		INNER JOIN m_pendidikan
			ON t_thl.pendidikan_id = m_pendidikan.id
        WHERE bidang_skpd_id = @bidang
		GROUP BY m_pendidikan.id;
	ELSEIF param_author = 5 THEN
		SELECT 	m_pendidikan.id			AS jenjang_id,
				m_pendidikan.deskripsi	AS jenjang,
				COUNT(m_pendidikan.id)	AS count_jenjang
		FROM t_thl
		INNER JOIN m_pendidikan
			ON t_thl.pendidikan_id = m_pendidikan.id
        WHERE kode_spv_kasie = param_kode
		GROUP BY m_pendidikan.id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_rekap_status_laporan_current_month` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
    
	SET @curr_date 	= CURDATE();
	SET @curr_month	= CONCAT(YEAR(@curr_date), '-', MONTH(@curr_date), '-01');
	SET @next_month = DATE_ADD(@curr_month, INTERVAL 1 MONTH);
    
	IF param_author = 1 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT 	t_laporan_kegiatan_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_kegiatan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_kegiatan_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
			GROUP BY t_laporan_kegiatan_thl.stat_laporan_id
			UNION ALL
			SELECT 	t_laporan_lain_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_lain_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_lain_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
			GROUP BY t_laporan_lain_thl.stat_laporan_id
		),
		status_laporan_sum (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	status_laporan_id			AS status_laporan_id,
					SUM(status_laporan_count)	AS status_laporan_count
			FROM status_laporan_count
			GROUP BY status_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_sum.status_laporan_count, status_laporan.status_laporan_count)		AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_sum
			ON status_laporan.status_laporan_id = status_laporan_sum.status_laporan_id;	
            
	ELSEIF param_author = 2 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT 	t_laporan_kegiatan_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_kegiatan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_kegiatan_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND t_laporan_thl.skpd_id = @skpd
			GROUP BY t_laporan_kegiatan_thl.stat_laporan_id
			UNION ALL
			SELECT 	t_laporan_lain_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_lain_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_lain_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND t_laporan_thl.skpd_id = @skpd
			GROUP BY t_laporan_lain_thl.stat_laporan_id
		),
		status_laporan_sum (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	status_laporan_id			AS status_laporan_id,
					SUM(status_laporan_count)	AS status_laporan_count
			FROM status_laporan_count
			GROUP BY status_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_sum.status_laporan_count, status_laporan.status_laporan_count)		AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_sum
			ON status_laporan.status_laporan_id = status_laporan_sum.status_laporan_id;
	
	ELSEIF param_author = 3 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT 	t_laporan_kegiatan_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_kegiatan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_kegiatan_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND t_laporan_thl.skpd_id = @skpd
			GROUP BY t_laporan_kegiatan_thl.stat_laporan_id
			UNION ALL
			SELECT 	t_laporan_lain_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_lain_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_lain_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND t_laporan_thl.skpd_id = @skpd
			GROUP BY t_laporan_lain_thl.stat_laporan_id
		),
		status_laporan_sum (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	status_laporan_id			AS status_laporan_id,
					SUM(status_laporan_count)	AS status_laporan_count
			FROM status_laporan_count
			GROUP BY status_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_sum.status_laporan_count, status_laporan.status_laporan_count)		AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_sum
			ON status_laporan.status_laporan_id = status_laporan_sum.status_laporan_id;	
            
	ELSEIF param_author = 4 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT 	t_laporan_kegiatan_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_kegiatan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_kegiatan_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND t_laporan_thl.bidang_skpd_id = @bidang
			GROUP BY t_laporan_kegiatan_thl.stat_laporan_id
			UNION ALL
			SELECT 	t_laporan_lain_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_lain_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_lain_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND t_laporan_thl.bidang_skpd_id = @bidang
			GROUP BY t_laporan_lain_thl.stat_laporan_id
		),
		status_laporan_sum (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	status_laporan_id			AS status_laporan_id,
					SUM(status_laporan_count)	AS status_laporan_count
			FROM status_laporan_count
			GROUP BY status_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_sum.status_laporan_count, status_laporan.status_laporan_count)		AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_sum
			ON status_laporan.status_laporan_id = status_laporan_sum.status_laporan_id;
	
	ELSEIF param_author = 5 AND param_kode IS NOT NULL THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT 	t_laporan_kegiatan_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_kegiatan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_kegiatan_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND	t_laporan_thl.kode_spv_kasie = param_kode
			GROUP BY t_laporan_kegiatan_thl.stat_laporan_id
			UNION ALL
			SELECT 	t_laporan_lain_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_lain_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_lain_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND	t_laporan_thl.kode_spv_kasie = param_kode
			GROUP BY t_laporan_lain_thl.stat_laporan_id
		),
		status_laporan_sum (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	status_laporan_id			AS status_laporan_id,
					SUM(status_laporan_count)	AS status_laporan_count
			FROM status_laporan_count
			GROUP BY status_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_sum.status_laporan_count, status_laporan.status_laporan_count)		AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_sum
			ON status_laporan.status_laporan_id = status_laporan_sum.status_laporan_id;
	
	ELSEIF param_author = 6 AND param_kode IS NOT NULL THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT 	t_laporan_kegiatan_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_kegiatan_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_kegiatan_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND	t_laporan_thl.kode_thl = param_kode
			GROUP BY t_laporan_kegiatan_thl.stat_laporan_id
			UNION ALL
			SELECT 	t_laporan_lain_thl.stat_laporan_id			AS status_laporan_id,
					COUNT(t_laporan_lain_thl.stat_laporan_id)	AS status_laporan_count
			FROM t_laporan_thl
			INNER JOIN t_laporan_lain_thl
				ON	t_laporan_thl.kode_laporan_thl = t_laporan_lain_thl.kode_laporan_thl
			WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND	t_laporan_thl.kode_thl = param_kode
			GROUP BY t_laporan_lain_thl.stat_laporan_id
		),
		status_laporan_sum (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	status_laporan_id			AS status_laporan_id,
					SUM(status_laporan_count)	AS status_laporan_count
			FROM status_laporan_count
			GROUP BY status_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_sum.status_laporan_count, status_laporan.status_laporan_count)		AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_sum
			ON status_laporan.status_laporan_id = status_laporan_sum.status_laporan_id;
            
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_rekap_status_target_current_month` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
    
	SET @curr_date 	= CURDATE();
	SET @curr_month	= CONCAT(YEAR(@curr_date), '-', MONTH(@curr_date), '-01');
	SET @next_month = DATE_ADD(@curr_month, INTERVAL 1 MONTH);
		
	IF param_author = 1 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id			AS status_laporan_id, 
					COUNT(stat_laporan_id)	AS status_laporan_count
			FROM t_target_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
			GROUP BY stat_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
            
	ELSEIF param_author = 2 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id			AS status_laporan_id, 
					COUNT(stat_laporan_id)	AS status_laporan_count
			FROM t_target_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND skpd_id = @skpd
			GROUP BY stat_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
            
	ELSEIF param_author = 3 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id			AS status_laporan_id, 
					COUNT(stat_laporan_id)	AS status_laporan_count
			FROM t_target_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND skpd_id = @skpd
			GROUP BY stat_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
            
	ELSEIF param_author = 4 THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id			AS status_laporan_id, 
					COUNT(stat_laporan_id)	AS status_laporan_count
			FROM t_target_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND bidang_skpd_id = @bidang
			GROUP BY stat_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
	
	ELSEIF param_author = 5 AND param_kode IS NOT NULL THEN
        WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id			AS status_laporan_id, 
					COUNT(stat_laporan_id)	AS status_laporan_count
			FROM t_target_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND	kode_spv_kasie = param_kode
			GROUP BY stat_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
	
	ELSEIF param_author = 6 AND param_kode IS NOT NULL THEN
		WITH status_laporan (status_laporan_id, status_laporan, status_laporan_count)
		AS
		(
			SELECT 	id 			AS status_laporan_id,
					deskripsi 	AS status_laporan,
					0			AS status_laporan_count
			FROM m_stat_laporan
		),
		status_laporan_count (status_laporan_id, status_laporan_count)
		AS
		(
			SELECT	stat_laporan_id			AS status_laporan_id, 
					COUNT(stat_laporan_id)	AS status_laporan_count
			FROM t_target_thl
			WHERE 	@curr_month <= tgl_laporan AND tgl_laporan < @next_month
				AND	kode_thl = param_kode
			GROUP BY stat_laporan_id
		)
		SELECT  status_laporan.status_laporan_id															AS status_laporan_id,
				status_laporan.status_laporan																AS status_laporan,
				COALESCE(status_laporan_count.status_laporan_count, status_laporan.status_laporan_count)	AS status_laporan_count
		FROM status_laporan
		LEFT JOIN status_laporan_count
			ON status_laporan.status_laporan_id = status_laporan_count.status_laporan_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_rekap_umur_thl` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
    
	IF param_author = 1 THEN
		WITH umur (umur)
		AS
		(
			SELECT 	TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())	AS umur
			FROM t_thl
		)
		SELECT 	SUM(IF(umur < 20,1,0)) 				AS '<20',
				SUM(IF(umur BETWEEN 20 AND 29,1,0)) AS '20-29',
				SUM(IF(umur BETWEEN 30 AND 39,1,0)) AS '30-39',
				SUM(IF(umur BETWEEN 40 AND 49,1,0)) AS '40-49',
				SUM(IF(umur BETWEEN 50 AND 59,1,0)) AS '50-60',
				SUM(IF(umur >=60, 1, 0)) as '>60'
		FROM umur;
	ELSEIF param_author = 2 THEN
		WITH umur (umur)
		AS
		(
			SELECT 	TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())	AS umur
			FROM t_thl
			WHERE skpd_id = @skpd
		)
		SELECT 	SUM(IF(umur < 20,1,0)) 				AS '<20',
				SUM(IF(umur BETWEEN 20 AND 29,1,0)) AS '20-29',
				SUM(IF(umur BETWEEN 30 AND 39,1,0)) AS '30-39',
				SUM(IF(umur BETWEEN 40 AND 49,1,0)) AS '40-49',
				SUM(IF(umur BETWEEN 50 AND 59,1,0)) AS '50-60',
				SUM(IF(umur >=60, 1, 0)) as '>60'
		FROM umur;
	ELSEIF param_author = 3 THEN
		WITH umur (umur)
		AS
		(
			SELECT 	TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())	AS umur
			FROM t_thl
			WHERE skpd_id = @skpd
		)
		SELECT 	SUM(IF(umur < 20,1,0)) 				AS '<20',
				SUM(IF(umur BETWEEN 20 AND 29,1,0)) AS '20-29',
				SUM(IF(umur BETWEEN 30 AND 39,1,0)) AS '30-39',
				SUM(IF(umur BETWEEN 40 AND 49,1,0)) AS '40-49',
				SUM(IF(umur BETWEEN 50 AND 59,1,0)) AS '50-60',
				SUM(IF(umur >=60, 1, 0)) as '>60'
		FROM umur;
	ELSEIF param_author = 4 THEN
		WITH umur (umur)
		AS
		(
			SELECT 	TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())	AS umur
			FROM t_thl
			WHERE bidang_skpd_id = @bidang
		)
		SELECT 	SUM(IF(umur < 20,1,0)) 				AS '<20',
				SUM(IF(umur BETWEEN 20 AND 29,1,0)) AS '20-29',
				SUM(IF(umur BETWEEN 30 AND 39,1,0)) AS '30-39',
				SUM(IF(umur BETWEEN 40 AND 49,1,0)) AS '40-49',
				SUM(IF(umur BETWEEN 50 AND 59,1,0)) AS '50-60',
				SUM(IF(umur >=60, 1, 0)) as '>60'
		FROM umur;
	ELSEIF param_author = 5 THEN
		WITH umur (umur)
		AS
		(
			SELECT 	TIMESTAMPDIFF(YEAR, tgl_lahir, CURDATE())	AS umur
			FROM t_thl
			WHERE kode_spv_kasie = param_kode
		)
		SELECT 	SUM(IF(umur < 20,1,0)) 				AS '<20',
				SUM(IF(umur BETWEEN 20 AND 29,1,0)) AS '20-29',
				SUM(IF(umur BETWEEN 30 AND 39,1,0)) AS '30-39',
				SUM(IF(umur BETWEEN 40 AND 49,1,0)) AS '40-49',
				SUM(IF(umur BETWEEN 50 AND 59,1,0)) AS '50-60',
				SUM(IF(umur >=60, 1, 0)) as '>60'
		FROM umur;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_beranda_summary_laporan_thl` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	SET @curr_date 	= CURDATE();
	SET @curr_month	= CONCAT(YEAR(@curr_date), '-', MONTH(@curr_date), '-01');
	SET @next_month = DATE_ADD(@curr_month, INTERVAL 1 MONTH);
    
    	IF param_author = 1 THEN
			SELECT 	t_laporan_thl.kode_laporan_thl							AS kode_thl,
					t_thl.nama												AS nama_thl,
					t_laporan_thl.tgl_laporan								AS tgl_laporan,
					GROUP_CONCAT(m_kegiatan_thl.deskripsi SEPARATOR ', ')	AS kegiatan
			FROM t_laporan_thl
			INNER JOIN t_thl
				ON t_laporan_thl.kode_thl = t_thl.kode_thl
			LEFT JOIN t_laporan_kegiatan_thl
				ON t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			LEFT JOIN m_kegiatan_thl
				ON m_kegiatan_thl.id = t_laporan_kegiatan_thl.kegiatan_thl_id
            WHERE @curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
			GROUP BY t_laporan_thl.kode_laporan_thl
            ORDER BY t_laporan_thl.tgl_laporan, t_thl.nama;
	
		ELSEIF param_author = 5 AND param_kode IS NOT NULL THEN
			SELECT 	t_laporan_thl.kode_laporan_thl							AS kode_thl,
					t_thl.nama												AS nama_thl,
					t_laporan_thl.tgl_laporan								AS tgl_laporan,
					GROUP_CONCAT(m_kegiatan_thl.deskripsi SEPARATOR ', ')	AS kegiatan
			FROM t_laporan_thl
			INNER JOIN t_thl
				ON t_laporan_thl.kode_thl = t_thl.kode_thl
			LEFT JOIN t_laporan_kegiatan_thl
				ON t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			LEFT JOIN m_kegiatan_thl
				ON m_kegiatan_thl.id = t_laporan_kegiatan_thl.kegiatan_thl_id
            WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND	t_laporan_thl.kode_spv = param_kode
			GROUP BY t_laporan_thl.kode_laporan_thl
            ORDER BY t_laporan_thl.tgl_laporan, t_thl.nama;
	
		ELSEIF param_author = 6 AND param_kode IS NOT NULL THEN
			SELECT 	t_laporan_thl.kode_laporan_thl							AS kode_thl,
					t_thl.nama												AS nama_thl,
					t_laporan_thl.tgl_laporan								AS tgl_laporan,
					GROUP_CONCAT(m_kegiatan_thl.deskripsi SEPARATOR ', ')	AS kegiatan
			FROM t_laporan_thl
			INNER JOIN t_thl
				ON t_laporan_thl.kode_thl = t_thl.kode_thl
			LEFT JOIN t_laporan_kegiatan_thl
				ON t_laporan_thl.kode_laporan_thl = t_laporan_kegiatan_thl.kode_laporan_thl
			LEFT JOIN m_kegiatan_thl
				ON m_kegiatan_thl.id = t_laporan_kegiatan_thl.kegiatan_thl_id
            WHERE 	@curr_month <= t_laporan_thl.tgl_laporan AND t_laporan_thl.tgl_laporan < @next_month
				AND	t_laporan_thl.kode_thl = param_kode
			GROUP BY t_laporan_thl.kode_laporan_thl
            ORDER BY t_laporan_thl.tgl_laporan, t_thl.nama;
            
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_detail_thl` (IN `param_kode` VARCHAR(165))  BEGIN
	SELECT 	m_skpd.deskripsi		AS nama_skpd,
			m_bidang_skpd.deskripsi	AS nama_bidang,
			m_profesi_thl.deskripsi	AS nama_profesi,
			t_thl.nama				AS nama_thl,
			spv_kabid.nama			AS nama_kabid,
			spv_kabid.nip			AS nip_kabid,
			spv_kadis.nama			AS nama_kadis,
			spv_kadis.nip			AS nip_kadis
	FROM t_thl
	INNER JOIN m_skpd
		ON m_skpd.id = t_thl.skpd_id
	INNER JOIN m_bidang_skpd
		ON m_bidang_skpd.id = t_thl.bidang_skpd_id
	INNER JOIN m_profesi_thl
		ON m_profesi_thl.id = t_thl.profesi_thl_id
	INNER JOIN t_spv AS spv_kabid
		ON t_thl.bidang_skpd_id = spv_kabid.bidang_skpd_id
	INNER JOIN t_akun AS akun_kabid
		ON spv_kabid.kode_spv = akun_kabid.kode
		AND akun_kabid.author_id = 4
	INNER JOIN t_spv AS spv_kadis
		ON t_thl.skpd_id = spv_kadis.skpd_id
	INNER JOIN t_akun AS akun_kadis
		ON spv_kadis.kode_spv = akun_kadis.kode
		AND akun_kadis.author_id = 3
	WHERE t_thl.kode_thl = param_kode;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_laporan_bulanan_thl` (IN `param_kode` VARCHAR(165), IN `param_month` DATE)  BEGIN
	SET @next_month = DATE_ADD(param_month, INTERVAL 1 MONTH);

	WITH
	laporan_thl(`laporan_id`,`tgl_laporan`,`kode_laporan_thl`)
	AS
	(
		SELECT `t_laporan_thl`.`id`                        	AS `laporan_id`,
				cast(`t_laporan_thl`.`tgl_laporan` AS date) AS `tgl_laporan`,
				`t_laporan_thl`.`kode_laporan_thl`			AS `kode_laporan_thl`
		FROM `t_laporan_thl`
		WHERE 	param_month <= `t_laporan_thl`.`tgl_laporan` AND `t_laporan_thl`.`tgl_laporan` < @next_month
			AND `t_laporan_thl`.`kode_thl` = param_kode
	),
	summary(`tgl_laporan`,`status_id`,`aktivitas`)
	AS
	(
		SELECT    cast(laporan_thl.tgl_laporan AS date) 		AS `tgl_laporan`,
				  `t_laporan_kegiatan_thl`.`stat_laporan_id`  	AS `status_id`,
				  `m_kegiatan_thl`.`deskripsi`  			  	AS `aktivitas`
		FROM      laporan_thl
		INNER JOIN `t_laporan_kegiatan_thl`
		ON        laporan_thl.kode_laporan_thl = `t_laporan_kegiatan_thl`.`kode_laporan_thl`
		INNER JOIN `m_kegiatan_thl`
		ON        `m_kegiatan_thl`.`id` = `t_laporan_kegiatan_thl`.`kegiatan_thl_id`
				
		UNION ALL
		SELECT    cast(laporan_thl.`tgl_laporan` AS date) 	AS `tgl_laporan`,
				  `t_laporan_lain_thl`.`stat_laporan_id`  	AS `status_id`,
				  `t_laporan_lain_thl`.`uraian`  	  		AS `aktivitas`
		FROM      laporan_thl
		INNER JOIN `t_laporan_lain_thl`
		ON        laporan_thl.kode_laporan_thl = `t_laporan_lain_thl`.`kode_laporan_thl`
	)

	SELECT 	summary.tgl_laporan			AS tanggal_laporan,
			summary.aktivitas			AS aktivitas,
			m_stat_laporan.deskripsi	AS status_aktivitas
	FROM summary
	INNER JOIN m_stat_laporan
	ON summary.status_id = m_stat_laporan.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_login` (IN `param_username` VARCHAR(125))  NO SQL
BEGIN
	SELECT 	t_akun.id											AS akun_id
			,m_author.id										AS author_id
            ,m_author.deskripsi									AS nama_author
			,t_akun.username									AS username
			,t_akun.password									AS password
			,m_stat_akun.id										AS status_akun
			,COALESCE(skpd_spv.id, skpd_thl.id)	AS skpd_id
			,COALESCE(t_spv.bidang_skpd_id, t_thl.bidang_skpd_id)	AS bidang_skpd_id
			,COALESCE(skpd_spv.deskripsi, skpd_thl.deskripsi)	AS nama_skpd
			,COALESCE(t_spv.nama, t_thl.nama)					AS nama_lengkap
            ,t_spv.kode_spv										AS kode_spv
			,t_spv.nip											AS nip
			,m_jabatan_spv.deskripsi							AS jabatan
            ,t_thl.kode_thl										AS kode_thl
			,t_thl.nik											AS nik
			,t_thl.email										AS email
			,t_thl.telepon										AS telepon
			,m_profesi_thl.deskripsi							AS profesi
	FROM t_akun
	INNER JOIN m_author
		ON t_akun.author_id = m_author.id
	INNER JOIN m_stat_akun
		ON t_akun.stat_akun_id = m_stat_akun.id
	LEFT JOIN t_spv
		ON t_akun.kode = t_spv.kode_spv
	LEFT JOIN m_skpd AS skpd_spv
		ON t_spv.skpd_id = skpd_spv.id
	LEFT JOIN m_jabatan_spv
		ON t_spv.jabatan_spv_id = m_jabatan_spv.id
	LEFT JOIN t_thl
		ON t_akun.kode = t_thl.kode_thl
	LEFT JOIN m_skpd AS skpd_thl
		ON t_thl.skpd_id = skpd_thl.id
	LEFT JOIN m_profesi_thl
		ON t_thl.profesi_thl_id = m_profesi_thl.id
	WHERE username = param_username;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_profil` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author = 6 THEN
		SELECT 	m_skpd.id										AS skpd_id
				,m_skpd.deskripsi								AS nama_skpd
                ,m_bidang_skpd.id								AS bidang_skpd_id
                ,m_bidang_skpd.deskripsi						AS nama_bidang_skpd
				,t_thl.nama										AS nama_lengkap
				,t_thl.kode_thl									AS kode_thl
				,t_thl.nik										AS nik
				,t_thl.email									AS email
				,t_thl.telepon									AS telepon
				,t_thl.tgl_lahir								AS tgl_lahir
				,wil_lahir.provinsi_id							AS provinsi_lahir_id
				,prov_lahir.deskripsi							AS provinsi_lahir
				,wil_lahir.id									AS wilayah_lahir_id
				,wil_lahir.deskripsi							AS wilayah_lahir
				,m_stat_perkawinan.id							AS status_perkawinan_id
				,m_stat_perkawinan.deskripsi					AS status_perkawinan
				,t_thl.alamat									AS alamat
				,prov_dom.id									AS provinsi_alamat_id
				,prov_dom.deskripsi								AS provinsi_alamat
				,wil_dom.id										AS wilayah_alamat_id
				,wil_dom.deskripsi								AS wilayah_alamat
				,m_profesi_thl.deskripsi						AS profesi
                ,IF(t_thl.pendidikan_id IS NULL, FALSE, TRUE)	AS pendidikan
		FROM t_thl
		INNER JOIN m_skpd
			ON t_thl.skpd_id = m_skpd.id
		INNER JOIN m_bidang_skpd
			ON t_thl.bidang_skpd_id = m_bidang_skpd.id
		INNER JOIN m_profesi_thl
			ON t_thl.profesi_thl_id = m_profesi_thl.id
		INNER JOIN m_wilayah	AS wil_lahir
			ON t_thl.tmpt_lahir = wil_lahir.id
		INNER JOIN m_provinsi	AS prov_lahir
			ON wil_lahir.provinsi_id = prov_lahir.id
		INNER JOIN m_wilayah	AS wil_dom
			ON t_thl.tmpt_asal = wil_dom.id
		INNER JOIN m_provinsi	AS prov_dom
			ON wil_dom.provinsi_id = prov_dom.id
		INNER JOIN m_stat_perkawinan
			ON t_thl.stat_perkawinan_id = m_stat_perkawinan.id
		WHERE t_thl.kode_thl = param_kode;
    ELSE
		SELECT 	m_skpd.id					AS skpd_id
				,m_skpd.deskripsi			AS nama_skpd
                ,m_bidang_skpd.id			AS bidang_skpd_id
                ,m_bidang_skpd.deskripsi	AS nama_bidang_skpd
				,t_spv.nama					AS nama_lengkap
				,t_spv.nip					AS nip
				,m_jabatan_spv.deskripsi	AS jabatan
		FROM t_spv
		INNER JOIN m_skpd
			ON t_spv.skpd_id = m_skpd.id
		INNER JOIN m_bidang_skpd
			ON t_spv.bidang_skpd_id = m_bidang_skpd.id
		INNER JOIN m_jabatan_spv
			ON t_spv.jabatan_spv_id = m_jabatan_spv.id
		WHERE t_spv.kode_spv = param_kode;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_rekap_aktivitas_thl` (IN `param_kode` VARCHAR(16))  NO SQL
BEGIN
	SET @year = YEAR(curdate());

	 WITH kegiatan(stat_kegiatan, rekap_kegiatan)
		AS
		(
    SELECT msl.id,COUNT(tlk.stat_laporan_id) rekap_kegiatan
    FROM t_laporan_thl tlt
    JOIN t_laporan_kegiatan_thl tlk ON tlk.kode_laporan_thl = tlt.kode_laporan_thl RIGHT JOIN m_stat_laporan msl ON
    msl.id = tlk.stat_laporan_id AND  tlt.kode_thl = param_kode AND YEAR(tlt.tgl_laporan) = @year
    GROUP BY msl.id,tlk.stat_laporan_id),
    lainnya(stat_lainnya, rekap_lainnya)
		AS
		(
    SELECT msl.id,COUNT(tll.stat_laporan_id) rekap_lainnya
    FROM t_laporan_thl tlt
    JOIN t_laporan_lain_thl tll ON tll.kode_laporan_thl = tlt.kode_laporan_thl 
    RIGHT JOIN m_stat_laporan msl ON
    msl.id = tll.stat_laporan_id AND  tlt.kode_thl = param_kode AND YEAR(tlt.tgl_laporan) = @year
    GROUP BY msl.id,tll.stat_laporan_id)
    SELECT msl.id, (kegiatan.rekap_kegiatan + lainnya.rekap_lainnya) rekap FROM m_stat_laporan msl JOIN lainnya ON lainnya.stat_lainnya = msl.id JOIN kegiatan ON kegiatan.stat_kegiatan = msl.id GROUP BY msl.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_target_bulanan_thl` (IN `param_kode` VARCHAR(165), IN `param_month` DATE)  BEGIN
	SET @next_month = DATE_ADD(param_month, INTERVAL 1 MONTH);

	SELECT 	t_target_thl.id				AS target_id,
			t_target_thl.tgl_laporan	AS tanggal_target,
			m_kegiatan_thl.deskripsi	AS target,
			m_stat_laporan.deskripsi	AS status_target
	FROM t_target_thl
	INNER JOIN m_stat_laporan
		ON t_target_thl.stat_laporan_id = m_stat_laporan.id
	LEFT JOIN t_target_detail_thl
		ON t_target_thl.kode_target_thl = t_target_detail_thl.kode_target_thl
	LEFT JOIN m_kegiatan_thl
		ON t_target_detail_thl.kegiatan_thl_id = m_kegiatan_thl.id
	WHERE 	param_month <= t_target_thl.tgl_laporan AND t_target_thl.tgl_laporan < @next_month
		AND t_target_thl.kode_thl = param_kode
        AND t_target_thl.stat_laporan_id = 3
	ORDER BY t_target_thl.id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_thl_list` (IN `param_author` TINYINT, IN `param_kode` VARCHAR(165))  BEGIN
	IF param_author <> 1 OR param_author <> 6 THEN
		SELECT bidang_skpd_id, skpd_id
		INTO @bidang, @skpd
		FROM t_spv
		WHERE kode_spv = param_kode;
    END IF;
	IF param_author = 1 THEN
		SELECT 	m_skpd.id				AS skpd_id,
				m_skpd.deskripsi		AS skpd,
				m_bidang_skpd.id		AS bidang_skpd_id,
				m_bidang_skpd.deskripsi	AS bidang_skpd,
				t_thl.kode_thl			AS kode_thl,
				t_thl.nama				AS nama_thl
		FROM m_skpd
		INNER JOIN m_bidang_skpd
			ON m_skpd.id = m_bidang_skpd.skpd_id
		LEFT JOIN t_thl
			ON 	t_thl.skpd_id = m_skpd.id
			AND	t_thl.bidang_skpd_id = m_bidang_skpd.id
		ORDER BY m_skpd.id, m_bidang_skpd.id;
        
	ELSEIF param_author = 2 THEN
		SELECT 	m_skpd.id				AS skpd_id,
				m_skpd.deskripsi		AS skpd,
				m_bidang_skpd.id		AS bidang_skpd_id,
				m_bidang_skpd.deskripsi	AS bidang_skpd,
				t_thl.kode_thl			AS kode_thl,
				t_thl.nama				AS nama_thl
		FROM m_skpd
		INNER JOIN m_bidang_skpd
			ON m_skpd.id = m_bidang_skpd.skpd_id
		LEFT JOIN t_thl
			ON 	t_thl.skpd_id = m_skpd.id
			AND	t_thl.bidang_skpd_id = m_bidang_skpd.id
		WHERE m_skpd.id = @skpd
		ORDER BY m_skpd.id, m_bidang_skpd.id;
    
	ELSEIF param_author = 3 THEN
		SELECT 	m_skpd.id				AS skpd_id,
				m_skpd.deskripsi		AS skpd,
				m_bidang_skpd.id		AS bidang_skpd_id,
				m_bidang_skpd.deskripsi	AS bidang_skpd,
				t_thl.kode_thl			AS kode_thl,
				t_thl.nama				AS nama_thl
		FROM m_skpd
		INNER JOIN m_bidang_skpd
			ON m_skpd.id = m_bidang_skpd.skpd_id
		LEFT JOIN t_thl
			ON 	t_thl.skpd_id = m_skpd.id
			AND	t_thl.bidang_skpd_id = m_bidang_skpd.id
		WHERE m_skpd.id = @skpd
		ORDER BY m_skpd.id, m_bidang_skpd.id;
    
	ELSEIF param_author = 4 THEN
		SELECT 	m_skpd.id				AS skpd_id,
				m_skpd.deskripsi		AS skpd,
				m_bidang_skpd.id		AS bidang_skpd_id,
				m_bidang_skpd.deskripsi	AS bidang_skpd,
				t_thl.kode_thl			AS kode_thl,
				t_thl.nama				AS nama_thl
		FROM m_skpd
		INNER JOIN m_bidang_skpd
			ON m_skpd.id = m_bidang_skpd.skpd_id
		LEFT JOIN t_thl
			ON 	t_thl.skpd_id = m_skpd.id
			AND	t_thl.bidang_skpd_id = m_bidang_skpd.id
		WHERE 	m_skpd.id 			= @skpd
			AND	m_bidang_skpd.id	= @bidang
		ORDER BY m_skpd.id, m_bidang_skpd.id;
    
	ELSEIF param_author = 5 AND param_kode IS NOT NULL THEN
		SELECT 	m_skpd.id				AS skpd_id,
				m_skpd.deskripsi		AS skpd,
				m_bidang_skpd.id		AS bidang_skpd_id,
				m_bidang_skpd.deskripsi	AS bidang_skpd,
				t_thl.kode_thl			AS kode_thl,
				t_thl.nama				AS nama_thl
		FROM m_skpd
		INNER JOIN m_bidang_skpd
			ON m_skpd.id = m_bidang_skpd.skpd_id
		LEFT JOIN t_thl
			ON 	t_thl.skpd_id = m_skpd.id
			AND	t_thl.bidang_skpd_id = m_bidang_skpd.id
		WHERE 	m_skpd.id 				= @skpd
			AND	m_bidang_skpd.id		= @bidang
            AND t_thl.kode_spv_kasie	= param_kode
		ORDER BY m_skpd.id, m_bidang_skpd.id;
    
	ELSEIF param_author = 6 AND param_kode IS NOT NULL THEN
		SELECT 	m_skpd.id				AS skpd_id,
				m_skpd.deskripsi		AS skpd,
				m_bidang_skpd.id		AS bidang_skpd_id,
				m_bidang_skpd.deskripsi	AS bidang_skpd,
				t_thl.kode_thl			AS kode_thl,
				t_thl.nama				AS nama_thl
		FROM m_skpd
		INNER JOIN m_bidang_skpd
			ON m_skpd.id = m_bidang_skpd.skpd_id
		LEFT JOIN t_thl
			ON 	t_thl.skpd_id = m_skpd.id
			AND	t_thl.bidang_skpd_id = m_bidang_skpd.id
		WHERE t_thl.kode_thl = param_kode
		ORDER BY m_skpd.id, m_bidang_skpd.id;
    
	ELSE
		SELECT 1 AS result;
        
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_pekerjaan` (IN `param_kode` VARCHAR(165), IN `param_instansi` VARCHAR(165), IN `param_posisi` VARCHAR(165), IN `param_masuk` DATE, IN `param_keluar` DATE, IN `param_lampiran` VARCHAR(165))  BEGIN
	INSERT INTO `trx_thl_pengalaman_kerja`
	(`kode_thl`,
	`instansi`,
	`deskripsi`,
	`dokumen`,
	`tgl_masuk`,
	`tgl_keluar`)
	VALUES
	(param_kode,
	param_instansi,
	param_posisi,
	param_lampiran,
	param_masuk,
	param_keluar);
    
    SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_pendidikan` (IN `param_kode` VARCHAR(165), IN `param_instansi` VARCHAR(165), IN `param_posisi` VARCHAR(165), IN `param_masuk` DATE, IN `param_keluar` DATE, IN `param_lampiran` VARCHAR(165))  BEGIN
	INSERT INTO `trx_thl_pengalaman_kerja`
	(`kode_thl`,
	`instansi`,
	`deskripsi`,
	`dokumen`,
	`tgl_masuk`,
	`tgl_keluar`)
	VALUES
	(param_kode,
	param_instansi,
	param_posisi,
	param_lampiran,
	param_masuk,
	param_keluar);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_sertifikasi` (IN `param_kode` VARCHAR(165), IN `param_sertifikat` VARCHAR(165), IN `param_lampiran` VARCHAR(165))  BEGIN
	INSERT INTO `silat_kominfo_new`.`trx_thl_sertfikat`
	(`kode_thl`,
	`deskripsi`,
	`dokumen`)
	VALUES
	(param_kode,
	param_sertifikat,
	param_lampiran);
    
    SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_password` (IN `param_id` INT, IN `param_password` VARCHAR(225))  NO SQL
BEGIN
	UPDATE t_akun
    SET	password 	= param_password
    WHERE id = param_id;
    
    SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_pekerjaan` (IN `param_id` INT, IN `param_kode` VARCHAR(165), IN `param_lampiran` VARCHAR(165))  BEGIN
	UPDATE `trx_thl_pengalaman_kerja`
	SET `dokumen` = COALESCE(param_lampiran, `dokumen`)
	WHERE `id` = param_id
    AND kode_thl = param_kode;

	SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_pendidikan` (IN `param_kode` VARCHAR(165), IN `param_lampiran` VARCHAR(165))  BEGIN
	UPDATE t_thl
	SET	ijazah = COALESCE(param_lampiran, ijazah)
	WHERE kode_thl = param_kode;
	
	SELECT 0 AS result;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_profil` (IN `param_id` INT, IN `param_author` TINYINT, IN `param_password` VARCHAR(225), IN `param_nip` VARCHAR(165), IN `param_nik` VARCHAR(16), IN `param_telepon` VARCHAR(25), IN `param_email` VARCHAR(165))  NO SQL
BEGIN
    DECLARE `_rollback` BOOL DEFAULT 0;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET `_rollback` = 1;
    START TRANSACTION;
    
	UPDATE t_akun
    SET	password 	= COALESCE(param_password, password)
    WHERE id = param_id;
	
    SELECT kode
    INTO @kode
    FROM t_akun
    WHERE id = param_id;
    
    IF param_author = 2 AND @kode IS NOT NULL THEN
		UPDATE t_spv
        SET nip	= param_nip
        WHERE kode_spv = @kode;
    ELSEIF param_author = 3 AND @kode IS NOT NULL THEN
		UPDATE t_thl
        SET	nik		= param_nik,
			telepon	= param_telepon,
            email	= param_email
		WHERE kode_thl = @kode;
    END IF;
    
	IF `_rollback` THEN
    BEGIN
        ROLLBACK;
		SELECT 1 AS result;
	END;
    ELSE
    BEGIN
        COMMIT;
		SELECT 0 AS result;
	END;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_sertifikasi` (IN `param_id` INT, IN `param_kode` VARCHAR(165), IN `param_lampiran` VARCHAR(165))  BEGIN
	UPDATE `trx_thl_sertfikat`
	SET `dokumen` = COALESCE(param_lampiran, `dokumen`)
	WHERE `id` = param_id
    AND kode_thl = param_kode;

    SELECT 0 AS result;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('e1umhk2is4ms3t4me5ermd5miq7hktom', '192.168.137.1', 1611544975, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534343638393b616b756e5f69647c733a323a223538223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a333a227a6f65223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a333a225a6f65223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22323567786e4e424367317a6a41223b6e696b7c733a333a22313233223b656d61696c7c733a31373a22746573744070617373776f72642e636f6d223b74656c65706f6e7c733a333a22303736223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('a6g5sslld14c81etmrjndihk0973kpaf', '10.58.2.63', 1611545853, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534353835333b),
('7gu46f2surp931k58se8ccubhlj2rhgo', '10.58.3.26', 1611545835, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534353833353b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('kk2ieqjnqcsveuip983gngr0fp80knnf', '10.58.1.102', 1611546167, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363136373b616b756e5f69647c733a323a223538223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a333a227a6f65223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a333a225a6f65223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22323567786e4e424367317a6a41223b6e696b7c733a333a22313233223b656d61696c7c733a31373a22746573744070617373776f72642e636f6d223b74656c65706f6e7c733a333a22303736223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('ot7d0c5e6am9vjdhirntvso8diadd234', '10.58.3.26', 1611546237, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363233373b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('2g4as25p6rh1qor2m53aalehdbsbo96u', '10.58.2.63', 1611546168, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363136383b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('3mbvpdtgcos7ampe63cvt2goh513fth1', '10.58.1.102', 1611546558, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363535383b616b756e5f69647c733a323a223538223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a333a227a6f65223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a333a225a6f65223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22323567786e4e424367317a6a41223b6e696b7c733a333a22313233223b656d61696c7c733a31373a22746573744070617373776f72642e636f6d223b74656c65706f6e7c733a333a22303736223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('2k847oqbd4bfrkublv9dg62hio59l4lk', '10.58.2.63', 1611546798, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363739383b616b756e5f69647c733a323a223438223b617574686f725f69647c733a313a2235223b6e616d615f617574686f727c733a32323a224b6570616c612053656b7369202f204b617375626167223b757365726e616d657c733a383a226368756e61697669223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a32343a224d4f43482c4348554e4149564920532e20532e4b6f6d0909223b6b6f64655f7370767c733a31333a223133645159564d48416e365151223b6e69707c733a31383a22313938333035303432303130303131303230223b6a61626174616e7c733a34303a224b4153492050454e47454d42414e47414e20262050454e47454c4f4c41414e2041504c494b415349223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('viqrlkalk58ddcos7vs0t59foi703mld', '10.58.3.26', 1611546795, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363739353b616b756e5f69647c733a323a223430223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a383a227461746172696e69223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a32363a225441544152494e492057554c414e444152492c532e4b6f6d0909223b6b6f64655f7370767c733a31333a2236386a6b524742775535673277223b6e69707c733a31383a22313937323132313431393936303332303031223b6a61626174616e7c733a32363a224b41424944204c4159414e414e20452d474f5645524e4d454e54223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('0lbt221htpnklort4kg44hhv2643lt5s', '10.58.1.102', 1611546967, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534363936373b616b756e5f69647c733a323a223538223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a333a227a6f65223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a333a225a6f65223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22323567786e4e424367317a6a41223b6e696b7c733a333a22313233223b656d61696c7c733a31373a22746573744070617373776f72642e636f6d223b74656c65706f6e7c733a333a22303736223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('lh4otqtian1khhvlfjqmbgqula4fleas', '10.58.3.26', 1611548466, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534383436363b616b756e5f69647c733a323a223430223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a383a227461746172696e69223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a32363a225441544152494e492057554c414e444152492c532e4b6f6d0909223b6b6f64655f7370767c733a31333a2236386a6b524742775535673277223b6e69707c733a31383a22313937323132313431393936303332303031223b6a61626174616e7c733a32363a224b41424944204c4159414e414e20452d474f5645524e4d454e54223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('2mcrshs8d2g6nrg29oo6r5a4eokmr61q', '10.58.2.63', 1611548192, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534383139323b616b756e5f69647c733a323a223438223b617574686f725f69647c733a313a2235223b6e616d615f617574686f727c733a32323a224b6570616c612053656b7369202f204b617375626167223b757365726e616d657c733a383a226368756e61697669223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a32343a224d4f43482c4348554e4149564920532e20532e4b6f6d0909223b6b6f64655f7370767c733a31333a223133645159564d48416e365151223b6e69707c733a31383a22313938333035303432303130303131303230223b6a61626174616e7c733a34303a224b4153492050454e47454d42414e47414e20262050454e47454c4f4c41414e2041504c494b415349223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('l391c2hdhu8174o9f3l6u82tgvtg2tbf', '10.58.2.63', 1611547335, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534373333353b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('7f9ooqhq7ps4qeskv55gpb18is9ldlme', '10.58.1.102', 1611550059, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535303035393b),
('jprco9cb7urs2natq00tspvce0od7pc7', '10.58.2.63', 1611547660, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534373636303b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('72gr5o6e4pp8v9b0nafaj8opk7o3bplk', '10.58.3.121', 1611561538, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534373334313b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('7ahhuff3lool1rkj4cktvvvq8k2a4ufg', '10.58.2.63', 1611548033, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534383033333b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('b5qq3qbom5oj65h70htl8dj1hq5gds3o', '10.58.2.63', 1611548883, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534383838333b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('bqgsa97cifvq91laiq8nnbuigml8nr98', '10.58.3.26', 1611548538, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534383533383b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('dvtek95e9t9779d6nokfcitsdtielq5q', '10.58.2.63', 1611550460, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535303436303b),
('f7ug4hb7tl5hdqabfn2qu3odm5avrbp1', '10.58.3.26', 1611548469, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534383436363b616b756e5f69647c733a323a223430223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a383a227461746172696e69223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2232223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a32363a225441544152494e492057554c414e444152492c532e4b6f6d0909223b6b6f64655f7370767c733a31333a2236386a6b524742775535673277223b6e69707c733a31383a22313937323132313431393936303332303031223b6a61626174616e7c733a32363a224b41424944204c4159414e414e20452d474f5645524e4d454e54223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('s8kajovao42tmbnq8a4m6sp582qkd93p', '10.58.3.26', 1611549097, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534393039373b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('h6n12h90fqjgp4aoo8v60g1prhc7a8u6', '10.58.2.63', 1611549370, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534393337303b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('bj9oa64n57ao8nvqfj6dam1hmgle6bvm', '10.58.3.26', 1611549412, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534393431323b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('tejtscq8ulhcec0kdfce9nfujc3re50s', '10.58.2.63', 1611549968, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534393936383b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('h4opuk714nl17usjcbphknr82ism7bid', '10.58.3.26', 1611549921, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534393932313b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('te42e1qukva6i3k45hgt7ogv14pcgfha', '10.58.3.26', 1611550452, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535303435323b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('5do4acbdd7tiacil7e2f2e83eaa050cl', '10.58.2.63', 1611550432, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313534393936383b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('o5dnsrgkgehf289b38dgqnvi7b2gssil', '10.58.1.102', 1611550681, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535303638313b616b756e5f69647c733a323a223733223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a393a2274686c5f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31323a224e616d61204c656e676b6170223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22333463613654343463574f5941223b6e696b7c733a343a2231323334223b656d61696c7c733a31313a22656d61696c40656d61696c223b74656c65706f6e7c733a363a22303831323232223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('of36jkluesqh2vj77vsdialhja6q0bdk', '10.58.2.63', 1611550776, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535303737363b616b756e5f69647c733a323a223639223b617574686f725f69647c733a313a2235223b6e616d615f617574686f727c733a32323a224b6570616c612053656b7369202f204b617375626167223b757365726e616d657c733a31333a227361726f6661685f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a373a225341524f464148223b6b6f64655f7370767c733a31333a223234676e534e61356655557541223b6e69707c733a31383a22313936333034313631393839313032303032223b6a61626174616e7c733a31393a224b617375626167204b6570656761776169616e223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('32m9lo66dh9rqqf4s18tlop04ce3dqgv', '10.58.3.26', 1611557010, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373031303b616b756e5f69647c733a323a223638223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a31323a22736974695f6d61726979616d223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a32373a22484a2e2053495449204d41524959414d2c20532e534f532c204d4d223b6b6f64655f7370767c733a31323a2239313566647941644957756b223b6e69707c733a31383a22313936373033313631393839303332303132223b6a61626174616e7c733a31303a2253656b72657461726973223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('p7ubbbrr190qmu8fhc8tbk05ioae5n2t', '10.58.2.63', 1611550920, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535303932303b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('l62cf5vjc31ue18m20qfcaorkj0s4l3a', '10.58.1.102', 1611556953, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535363935333b616b756e5f69647c733a323a223733223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a393a2274686c5f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31323a224e616d61204c656e676b6170223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22333463613654343463574f5941223b6e696b7c733a343a2231323334223b656d61696c7c733a31313a22656d61696c40656d61696c223b74656c65706f6e7c733a363a22303831323232223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('k0lam893nmf01m2ncqnsmf7r87sieu65', '10.58.2.63', 1611557139, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373133393b616b756e5f69647c733a323a223639223b617574686f725f69647c733a313a2235223b6e616d615f617574686f727c733a32323a224b6570616c612053656b7369202f204b617375626167223b757365726e616d657c733a31333a227361726f6661685f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a373a225341524f464148223b6b6f64655f7370767c733a31333a223234676e534e61356655557541223b6e69707c733a31383a22313936333034313631393839313032303032223b6a61626174616e7c733a31393a224b617375626167204b6570656761776169616e223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('p8h9rtp8psg7kavlbdfpic0rafe7q5a7', '10.58.2.63', 1611557044, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373034343b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('0rs5sidlc5b1gr9lc33oppkukjputf41', '::1', 1611555672, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535353637323b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('osejbdfblgg04ieu70gk9g1nonebu766', '::1', 1611556019, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535363031393b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('o3j4u4h544sfom1v91u2m6a3pnhlgfn1', '::1', 1611556378, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535363337383b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('q4lo36phvcnmull4ikk40te6bqfjrfp6', '::1', 1611556703, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535363730333b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('lqgv3gs4mm9h6ssal5u9q2qmotsrrsvc', '::1', 1611560066, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313536303036363b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('p6ud60noqvjie7qjc209q2tlen047v1n', '10.58.1.102', 1611557297, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373239373b616b756e5f69647c733a323a223733223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a393a2274686c5f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31323a224e616d61204c656e676b6170223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22333463613654343463574f5941223b6e696b7c733a343a2231323334223b656d61696c7c733a31313a22656d61696c40656d61696c223b74656c65706f6e7c733a363a22303831323232223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('6kka3s7o8s0tlbne4vgnen874i7u0i50', '10.58.3.26', 1611557343, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373334333b616b756e5f69647c733a323a223638223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a31323a22736974695f6d61726979616d223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a32373a22484a2e2053495449204d41524959414d2c20532e534f532c204d4d223b6b6f64655f7370767c733a31323a2239313566647941644957756b223b6e69707c733a31383a22313936373033313631393839303332303132223b6a61626174616e7c733a31303a2253656b72657461726973223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('6etvtkvdmoisotm2ubgbdgp6lb4ehrsb', '10.58.2.63', 1611557498, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373439383b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('50db3ch2g640tlupeqr7as2ohcqrskuf', '10.58.2.63', 1611557170, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373133393b616b756e5f69647c733a323a223639223b617574686f725f69647c733a313a2235223b6e616d615f617574686f727c733a32323a224b6570616c612053656b7369202f204b617375626167223b757365726e616d657c733a31333a227361726f6661685f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a373a225341524f464148223b6b6f64655f7370767c733a31333a223234676e534e61356655557541223b6e69707c733a31383a22313936333034313631393839313032303032223b6a61626174616e7c733a31393a224b617375626167204b6570656761776169616e223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('ltli8g7c04khhi45cgkg5sl1ele15p4a', '10.58.1.102', 1611560087, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313536303038373b616b756e5f69647c733a323a223733223b617574686f725f69647c733a313a2236223b6e616d615f617574686f727c733a353a225374616666223b757365726e616d657c733a393a2274686c5f636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31323a224e616d61204c656e676b6170223b6b6f64655f7370767c4e3b6e69707c4e3b6a61626174616e7c4e3b6b6f64655f74686c7c733a31333a22333463613654343463574f5941223b6e696b7c733a343a2231323334223b656d61696c7c733a31313a22656d61696c40656d61696c223b74656c65706f6e7c733a363a22303831323232223b70726f666573697c733a31303a2250726f6772616d6d6572223b69735f6c6f67696e7c623a313b),
('3pt4q2lhnhaaqqt5d5k3bepbf1advoj2', '10.58.3.26', 1611557687, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373638373b616b756e5f69647c733a323a223638223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a31323a22736974695f6d61726979616d223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a32373a22484a2e2053495449204d41524959414d2c20532e534f532c204d4d223b6b6f64655f7370767c733a31323a2239313566647941644957756b223b6e69707c733a31383a22313936373033313631393839303332303132223b6a61626174616e7c733a31303a2253656b72657461726973223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('rtjtbg5bn272u1183sj7lga227qrudpb', '10.58.2.63', 1611557591, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373439383b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('lm8nkhac8gisei6idvttqbpqsto9n0fr', '10.58.3.26', 1611557758, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313535373638373b616b756e5f69647c733a323a223638223b617574686f725f69647c733a313a2234223b6e616d615f617574686f727c733a32363a224b6570616c6120426964616e67202f2053656b72657461726973223b757365726e616d657c733a31323a22736974695f6d61726979616d223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a32373a22484a2e2053495449204d41524959414d2c20532e534f532c204d4d223b6b6f64655f7370767c733a31323a2239313566647941644957756b223b6e69707c733a31383a22313936373033313631393839303332303132223b6a61626174616e7c733a31303a2253656b72657461726973223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('7sg363eck2kp4ep91204sqm113bq8lh5', '::1', 1611560318, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313536303036363b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('f87abj6b91jpiptocfehh1uqvkh30nrd', '10.58.1.102', 1611560114, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313536303038373b616b756e5f69647c733a323a223635223b617574686f725f69647c733a313a2232223b6e616d615f617574686f727c733a353a2261646d696e223b757365726e616d657c733a31303a2261646d696e636170696c223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2239223b626964616e675f736b70645f69647c733a323a223232223b6e616d615f736b70647c733a33363a2244696e6173204b6570656e647564756b616e2064616e204361746174616e20536970696c223b6e616d615f6c656e676b61707c733a31313a2261646d696e20636170696c223b6b6f64655f7370767c733a31323a2231357248596830566b74316b223b6e69707c733a393a22313233313331333133223b6a61626174616e7c733a31303a2261646d696e2028514329223b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('co5piekicbtbaf5lbu62uknqueivkptd', '10.58.2.63', 1611561584, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313536313131373b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('jgbj42teo24b922tm1mbav5jbm3pjpjl', '::1', 1611623135, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313632333133353b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('404kqonntsdmc4l0c9q5onnnec67h143', '::1', 1611623912, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313632333931323b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('33rg02eb1gootleeoac52g1rhi1fjd43', '::1', 1611624569, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313632343536393b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b),
('e8d9mhtn21eju910425ltn4iq8nf79at', '::1', 1611624724, 0x5f5f63695f6c6173745f726567656e65726174657c693a313631313632343536393b616b756e5f69647c733a313a2236223b617574686f725f69647c733a313a2231223b6e616d615f617574686f727c733a31333a2261646d696e6973747261746f72223b757365726e616d657c733a31303a22737570657261646d696e223b7374617475735f616b756e7c733a313a2231223b736b70645f69647c733a313a2231223b626964616e675f736b70645f69647c733a313a2231223b6e616d615f736b70647c733a33333a2244696e6173204b6f6d756e696b6173692064616e20496e666f726d6174696b6120223b6e616d615f6c656e676b61707c733a31303a22537570657261646d696e223b6b6f64655f7370767c733a31333a223531454e3731634542304b516f223b6e69707c733a31373a223132333435343637373839313233343536223b6a61626174616e7c4e3b6b6f64655f74686c7c4e3b6e696b7c4e3b656d61696c7c4e3b74656c65706f6e7c4e3b70726f666573697c4e3b69735f6c6f67696e7c623a313b);

-- --------------------------------------------------------

--
-- Table structure for table `m_author`
--

CREATE TABLE `m_author` (
  `id` tinyint(4) NOT NULL,
  `deskripsi` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_author`
--

INSERT INTO `m_author` (`id`, `deskripsi`) VALUES
(1, 'administrator'),
(2, 'admin'),
(3, 'Kepala dinas'),
(4, 'Kepala Bidang / Sekretaris'),
(5, 'Kepala Seksi / Kasubag'),
(6, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `m_bidang_skpd`
--

CREATE TABLE `m_bidang_skpd` (
  `id` mediumint(9) NOT NULL,
  `skpd_id` smallint(6) NOT NULL,
  `deskripsi` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_bidang_skpd`
--

INSERT INTO `m_bidang_skpd` (`id`, `skpd_id`, `deskripsi`) VALUES
(1, 1, 'Sekretariat'),
(2, 1, 'E-Governtment'),
(19, 1, 'IKP'),
(20, 1, 'Statistik'),
(22, 9, 'Sekretariat'),
(23, 9, 'Kependudukan'),
(24, 9, 'Pencatatan Sipil');

-- --------------------------------------------------------

--
-- Table structure for table `m_jabatan_spv`
--

CREATE TABLE `m_jabatan_spv` (
  `id` smallint(6) NOT NULL,
  `bidang_skpd_id` smallint(6) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `author_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_jabatan_spv`
--

INSERT INTO `m_jabatan_spv` (`id`, `bidang_skpd_id`, `deskripsi`, `author_id`) VALUES
(1, 1, 'Superadmin', 1),
(2, 1, 'admin (QC)', 2),
(3, 1, 'Kepala Dinas Kominfo', 3),
(12, 19, 'KABID PENGELOLAAN INFOKOM PUBLIK', 4),
(13, 2, 'KABID LAYANAN E-GOVERNMENT', 4),
(14, 2, 'KASI INSFRASTRUKTUR JARINGAN', 5),
(15, 19, 'KASI PENGELOLAAN KOMUNIKASI PUBLIK', 5),
(16, 19, 'KASI PENGELOLAAN INFORMASI PUBLIK', 5),
(17, 19, 'KASI LAYANAN INFORMASI DAN HUBUNGAN MEDIA', 5),
(18, 20, 'KASI PENGELOLAAN DATA STATISTIK SEKTOR I', 5),
(19, 2, 'KASI LAYANAN E-GOVERNMENT DAN PERSANDIAN', 5),
(20, 2, 'KASI PENGEMBANGAN & PENGELOLAAN APLIKASI', 5),
(21, 20, 'KASI PENGELOLAAN DATA STATISTIK SEKTOR II', 5),
(22, 2, 'ANALIS SISTEM INFORMASI', 5),
(23, 1, 'PENYUSUN PROGRAM ANGGARAN DAN KEUANGAN', 5),
(24, 1, 'Sekretaris 1', 4),
(26, 22, 'Kepala Dinas Capil', 3),
(27, 22, 'Sekretaris', 4),
(28, 22, 'Kasubag Kepegawaian', 5),
(29, 23, 'Kabid Kependudukan', 4),
(30, 24, 'Kabid Pencatatan Sipil', 4),
(31, 23, 'Kasi Kependudukan', 5),
(32, 24, 'Kasi Pencatatan Sipil', 5);

-- --------------------------------------------------------

--
-- Table structure for table `m_kegiatan_thl`
--

CREATE TABLE `m_kegiatan_thl` (
  `id` smallint(6) NOT NULL,
  `profesi_thl_id` smallint(6) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `flag` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_kegiatan_thl`
--

INSERT INTO `m_kegiatan_thl` (`id`, `profesi_thl_id`, `deskripsi`, `keterangan`, `flag`) VALUES
(1, 1, 'Coding', 'Pemrograman', 1),
(2, 5, 'Setting Mikrotik', '-0\r\n', 1),
(3, 1, 'Buat Mockup', '-', 0),
(5, 1, 'Fix Bug', 'tet\r\n', 1),
(6, 1, 'Analisa', '-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `m_pendidikan`
--

CREATE TABLE `m_pendidikan` (
  `id` tinyint(4) NOT NULL,
  `deskripsi` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_pendidikan`
--

INSERT INTO `m_pendidikan` (`id`, `deskripsi`) VALUES
(1, 'TK'),
(2, 'SD/MI'),
(3, 'SMP/MTs'),
(4, 'SMA/MA/MAK'),
(5, 'Diploma I'),
(6, 'Diploma III'),
(7, 'Diploma IV'),
(8, 'Sarjana'),
(9, 'Magister'),
(10, 'Doktor');

-- --------------------------------------------------------

--
-- Table structure for table `m_profesi_thl`
--

CREATE TABLE `m_profesi_thl` (
  `id` smallint(6) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `keterangan` varchar(225) NOT NULL,
  `flag` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_profesi_thl`
--

INSERT INTO `m_profesi_thl` (`id`, `deskripsi`, `keterangan`, `flag`) VALUES
(1, 'Programmer', 'Bertugas Sebagai Pemogram Aplikasi', 1),
(2, 'Pengolah Data', 'Bertugas sebagai pemegang administrasi data dinas', 1),
(4, 'Multimedia', 'Design\r\n', 1),
(5, 'Jaringan', 'Mengelola Seluruh Jaringan', 1),
(6, 'Pengolah Berkas', '-', 1),
(7, 'Videografer', '-', 1);

-- --------------------------------------------------------

--
-- Table structure for table `m_provinsi`
--

CREATE TABLE `m_provinsi` (
  `id` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_provinsi`
--

INSERT INTO `m_provinsi` (`id`, `deskripsi`) VALUES
('11', 'ACEH'),
('12', 'SUMATERA UTARA'),
('13', 'SUMATERA BARAT'),
('14', 'RIAU'),
('15', 'JAMBI'),
('16', 'SUMATERA SELATAN'),
('17', 'BENGKULU'),
('18', 'LAMPUNG'),
('19', 'KEPULAUAN BANGKA BELITUNG'),
('21', 'KEPULAUAN RIAU'),
('31', 'DKI JAKARTA'),
('32', 'JAWA BARAT'),
('33', 'JAWA TENGAH'),
('34', 'DI YOGYAKARTA'),
('35', 'JAWA TIMUR'),
('36', 'BANTEN'),
('51', 'BALI'),
('52', 'NUSA TENGGARA BARAT'),
('53', 'NUSA TENGGARA TIMUR'),
('61', 'KALIMANTAN BARAT'),
('62', 'KALIMANTAN TENGAH'),
('63', 'KALIMANTAN SELATAN'),
('64', 'KALIMANTAN TIMUR'),
('65', 'KALIMANTAN UTARA'),
('71', 'SULAWESI UTARA'),
('72', 'SULAWESI TENGAH'),
('73', 'SULAWESI SELATAN'),
('74', 'SULAWESI TENGGARA'),
('75', 'GORONTALO'),
('76', 'SULAWESI BARAT'),
('81', 'MALUKU'),
('82', 'MALUKU UTARA'),
('91', 'PAPUA BARAT'),
('94', 'PAPUA');

-- --------------------------------------------------------

--
-- Table structure for table `m_skpd`
--

CREATE TABLE `m_skpd` (
  `id` smallint(6) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `no_telp` varchar(25) DEFAULT NULL,
  `email` varchar(165) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_skpd`
--

INSERT INTO `m_skpd` (`id`, `deskripsi`, `alamat`, `no_telp`, `email`) VALUES
(1, 'Dinas Komunikasi dan Informatika ', 'Jl. A. Yani 12 kota pasuruan', 'qwqw', 'qwqw@pasurunkota'),
(9, 'Dinas Kependudukan dan Catatan Sipil', '-', '07', 'pasuruankota@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `m_stat_akun`
--

CREATE TABLE `m_stat_akun` (
  `id` tinyint(4) NOT NULL,
  `deskripsi` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_stat_akun`
--

INSERT INTO `m_stat_akun` (`id`, `deskripsi`) VALUES
(1, 'Aktif'),
(2, 'Non-Aktif'),
(3, 'Terhapus');

-- --------------------------------------------------------

--
-- Table structure for table `m_stat_laporan`
--

CREATE TABLE `m_stat_laporan` (
  `id` tinyint(4) NOT NULL,
  `deskripsi` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_stat_laporan`
--

INSERT INTO `m_stat_laporan` (`id`, `deskripsi`) VALUES
(1, 'Verifikasi Kasie'),
(2, 'Verifikasi Kabid'),
(3, 'Disetujui'),
(4, 'Ditolak Kasie'),
(5, 'Ditolak Kabid');

-- --------------------------------------------------------

--
-- Table structure for table `m_stat_perkawinan`
--

CREATE TABLE `m_stat_perkawinan` (
  `id` tinyint(4) NOT NULL,
  `deskripsi` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_stat_perkawinan`
--

INSERT INTO `m_stat_perkawinan` (`id`, `deskripsi`) VALUES
(1, 'Belum Kawin'),
(2, 'Kawin Tercatat'),
(3, 'Kawin Belum Tercatat'),
(4, 'Cerai Hidup'),
(5, 'Cerai Mati');

-- --------------------------------------------------------

--
-- Table structure for table `m_wilayah`
--

CREATE TABLE `m_wilayah` (
  `id` char(4) COLLATE utf8_unicode_ci NOT NULL,
  `provinsi_id` char(2) COLLATE utf8_unicode_ci NOT NULL,
  `deskripsi` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `m_wilayah`
--

INSERT INTO `m_wilayah` (`id`, `provinsi_id`, `deskripsi`) VALUES
('1101', '11', 'KABUPATEN SIMEULUE'),
('1102', '11', 'KABUPATEN ACEH SINGKIL'),
('1103', '11', 'KABUPATEN ACEH SELATAN'),
('1104', '11', 'KABUPATEN ACEH TENGGARA'),
('1105', '11', 'KABUPATEN ACEH TIMUR'),
('1106', '11', 'KABUPATEN ACEH TENGAH'),
('1107', '11', 'KABUPATEN ACEH BARAT'),
('1108', '11', 'KABUPATEN ACEH BESAR'),
('1109', '11', 'KABUPATEN PIDIE'),
('1110', '11', 'KABUPATEN BIREUEN'),
('1111', '11', 'KABUPATEN ACEH UTARA'),
('1112', '11', 'KABUPATEN ACEH BARAT DAYA'),
('1113', '11', 'KABUPATEN GAYO LUES'),
('1114', '11', 'KABUPATEN ACEH TAMIANG'),
('1115', '11', 'KABUPATEN NAGAN RAYA'),
('1116', '11', 'KABUPATEN ACEH JAYA'),
('1117', '11', 'KABUPATEN BENER MERIAH'),
('1118', '11', 'KABUPATEN PIDIE JAYA'),
('1171', '11', 'KOTA BANDA ACEH'),
('1172', '11', 'KOTA SABANG'),
('1173', '11', 'KOTA LANGSA'),
('1174', '11', 'KOTA LHOKSEUMAWE'),
('1175', '11', 'KOTA SUBULUSSALAM'),
('1201', '12', 'KABUPATEN NIAS'),
('1202', '12', 'KABUPATEN MANDAILING NATAL'),
('1203', '12', 'KABUPATEN TAPANULI SELATAN'),
('1204', '12', 'KABUPATEN TAPANULI TENGAH'),
('1205', '12', 'KABUPATEN TAPANULI UTARA'),
('1206', '12', 'KABUPATEN TOBA SAMOSIR'),
('1207', '12', 'KABUPATEN LABUHAN BATU'),
('1208', '12', 'KABUPATEN ASAHAN'),
('1209', '12', 'KABUPATEN SIMALUNGUN'),
('1210', '12', 'KABUPATEN DAIRI'),
('1211', '12', 'KABUPATEN KARO'),
('1212', '12', 'KABUPATEN DELI SERDANG'),
('1213', '12', 'KABUPATEN LANGKAT'),
('1214', '12', 'KABUPATEN NIAS SELATAN'),
('1215', '12', 'KABUPATEN HUMBANG HASUNDUTAN'),
('1216', '12', 'KABUPATEN PAKPAK BHARAT'),
('1217', '12', 'KABUPATEN SAMOSIR'),
('1218', '12', 'KABUPATEN SERDANG BEDAGAI'),
('1219', '12', 'KABUPATEN BATU BARA'),
('1220', '12', 'KABUPATEN PADANG LAWAS UTARA'),
('1221', '12', 'KABUPATEN PADANG LAWAS'),
('1222', '12', 'KABUPATEN LABUHAN BATU SELATAN'),
('1223', '12', 'KABUPATEN LABUHAN BATU UTARA'),
('1224', '12', 'KABUPATEN NIAS UTARA'),
('1225', '12', 'KABUPATEN NIAS BARAT'),
('1271', '12', 'KOTA SIBOLGA'),
('1272', '12', 'KOTA TANJUNG BALAI'),
('1273', '12', 'KOTA PEMATANG SIANTAR'),
('1274', '12', 'KOTA TEBING TINGGI'),
('1275', '12', 'KOTA MEDAN'),
('1276', '12', 'KOTA BINJAI'),
('1277', '12', 'KOTA PADANGSIDIMPUAN'),
('1278', '12', 'KOTA GUNUNGSITOLI'),
('1301', '13', 'KABUPATEN KEPULAUAN MENTAWAI'),
('1302', '13', 'KABUPATEN PESISIR SELATAN'),
('1303', '13', 'KABUPATEN SOLOK'),
('1304', '13', 'KABUPATEN SIJUNJUNG'),
('1305', '13', 'KABUPATEN TANAH DATAR'),
('1306', '13', 'KABUPATEN PADANG PARIAMAN'),
('1307', '13', 'KABUPATEN AGAM'),
('1308', '13', 'KABUPATEN LIMA PULUH KOTA'),
('1309', '13', 'KABUPATEN PASAMAN'),
('1310', '13', 'KABUPATEN SOLOK SELATAN'),
('1311', '13', 'KABUPATEN DHARMASRAYA'),
('1312', '13', 'KABUPATEN PASAMAN BARAT'),
('1371', '13', 'KOTA PADANG'),
('1372', '13', 'KOTA SOLOK'),
('1373', '13', 'KOTA SAWAH LUNTO'),
('1374', '13', 'KOTA PADANG PANJANG'),
('1375', '13', 'KOTA BUKITTINGGI'),
('1376', '13', 'KOTA PAYAKUMBUH'),
('1377', '13', 'KOTA PARIAMAN'),
('1401', '14', 'KABUPATEN KUANTAN SINGINGI'),
('1402', '14', 'KABUPATEN INDRAGIRI HULU'),
('1403', '14', 'KABUPATEN INDRAGIRI HILIR'),
('1404', '14', 'KABUPATEN PELALAWAN'),
('1405', '14', 'KABUPATEN S I A K'),
('1406', '14', 'KABUPATEN KAMPAR'),
('1407', '14', 'KABUPATEN ROKAN HULU'),
('1408', '14', 'KABUPATEN BENGKALIS'),
('1409', '14', 'KABUPATEN ROKAN HILIR'),
('1410', '14', 'KABUPATEN KEPULAUAN MERANTI'),
('1471', '14', 'KOTA PEKANBARU'),
('1473', '14', 'KOTA D U M A I'),
('1501', '15', 'KABUPATEN KERINCI'),
('1502', '15', 'KABUPATEN MERANGIN'),
('1503', '15', 'KABUPATEN SAROLANGUN'),
('1504', '15', 'KABUPATEN BATANG HARI'),
('1505', '15', 'KABUPATEN MUARO JAMBI'),
('1506', '15', 'KABUPATEN TANJUNG JABUNG TIMUR'),
('1507', '15', 'KABUPATEN TANJUNG JABUNG BARAT'),
('1508', '15', 'KABUPATEN TEBO'),
('1509', '15', 'KABUPATEN BUNGO'),
('1571', '15', 'KOTA JAMBI'),
('1572', '15', 'KOTA SUNGAI PENUH'),
('1601', '16', 'KABUPATEN OGAN KOMERING ULU'),
('1602', '16', 'KABUPATEN OGAN KOMERING ILIR'),
('1603', '16', 'KABUPATEN MUARA ENIM'),
('1604', '16', 'KABUPATEN LAHAT'),
('1605', '16', 'KABUPATEN MUSI RAWAS'),
('1606', '16', 'KABUPATEN MUSI BANYUASIN'),
('1607', '16', 'KABUPATEN BANYU ASIN'),
('1608', '16', 'KABUPATEN OGAN KOMERING ULU SELATAN'),
('1609', '16', 'KABUPATEN OGAN KOMERING ULU TIMUR'),
('1610', '16', 'KABUPATEN OGAN ILIR'),
('1611', '16', 'KABUPATEN EMPAT LAWANG'),
('1612', '16', 'KABUPATEN PENUKAL ABAB LEMATANG ILIR'),
('1613', '16', 'KABUPATEN MUSI RAWAS UTARA'),
('1671', '16', 'KOTA PALEMBANG'),
('1672', '16', 'KOTA PRABUMULIH'),
('1673', '16', 'KOTA PAGAR ALAM'),
('1674', '16', 'KOTA LUBUKLINGGAU'),
('1701', '17', 'KABUPATEN BENGKULU SELATAN'),
('1702', '17', 'KABUPATEN REJANG LEBONG'),
('1703', '17', 'KABUPATEN BENGKULU UTARA'),
('1704', '17', 'KABUPATEN KAUR'),
('1705', '17', 'KABUPATEN SELUMA'),
('1706', '17', 'KABUPATEN MUKOMUKO'),
('1707', '17', 'KABUPATEN LEBONG'),
('1708', '17', 'KABUPATEN KEPAHIANG'),
('1709', '17', 'KABUPATEN BENGKULU TENGAH'),
('1771', '17', 'KOTA BENGKULU'),
('1801', '18', 'KABUPATEN LAMPUNG BARAT'),
('1802', '18', 'KABUPATEN TANGGAMUS'),
('1803', '18', 'KABUPATEN LAMPUNG SELATAN'),
('1804', '18', 'KABUPATEN LAMPUNG TIMUR'),
('1805', '18', 'KABUPATEN LAMPUNG TENGAH'),
('1806', '18', 'KABUPATEN LAMPUNG UTARA'),
('1807', '18', 'KABUPATEN WAY KANAN'),
('1808', '18', 'KABUPATEN TULANGBAWANG'),
('1809', '18', 'KABUPATEN PESAWARAN'),
('1810', '18', 'KABUPATEN PRINGSEWU'),
('1811', '18', 'KABUPATEN MESUJI'),
('1812', '18', 'KABUPATEN TULANG BAWANG BARAT'),
('1813', '18', 'KABUPATEN PESISIR BARAT'),
('1871', '18', 'KOTA BANDAR LAMPUNG'),
('1872', '18', 'KOTA METRO'),
('1901', '19', 'KABUPATEN BANGKA'),
('1902', '19', 'KABUPATEN BELITUNG'),
('1903', '19', 'KABUPATEN BANGKA BARAT'),
('1904', '19', 'KABUPATEN BANGKA TENGAH'),
('1905', '19', 'KABUPATEN BANGKA SELATAN'),
('1906', '19', 'KABUPATEN BELITUNG TIMUR'),
('1971', '19', 'KOTA PANGKAL PINANG'),
('2101', '21', 'KABUPATEN KARIMUN'),
('2102', '21', 'KABUPATEN BINTAN'),
('2103', '21', 'KABUPATEN NATUNA'),
('2104', '21', 'KABUPATEN LINGGA'),
('2105', '21', 'KABUPATEN KEPULAUAN ANAMBAS'),
('2171', '21', 'KOTA B A T A M'),
('2172', '21', 'KOTA TANJUNG PINANG'),
('3101', '31', 'KABUPATEN KEPULAUAN SERIBU'),
('3171', '31', 'KOTA JAKARTA SELATAN'),
('3172', '31', 'KOTA JAKARTA TIMUR'),
('3173', '31', 'KOTA JAKARTA PUSAT'),
('3174', '31', 'KOTA JAKARTA BARAT'),
('3175', '31', 'KOTA JAKARTA UTARA'),
('3201', '32', 'KABUPATEN BOGOR'),
('3202', '32', 'KABUPATEN SUKABUMI'),
('3203', '32', 'KABUPATEN CIANJUR'),
('3204', '32', 'KABUPATEN BANDUNG'),
('3205', '32', 'KABUPATEN GARUT'),
('3206', '32', 'KABUPATEN TASIKMALAYA'),
('3207', '32', 'KABUPATEN CIAMIS'),
('3208', '32', 'KABUPATEN KUNINGAN'),
('3209', '32', 'KABUPATEN CIREBON'),
('3210', '32', 'KABUPATEN MAJALENGKA'),
('3211', '32', 'KABUPATEN SUMEDANG'),
('3212', '32', 'KABUPATEN INDRAMAYU'),
('3213', '32', 'KABUPATEN SUBANG'),
('3214', '32', 'KABUPATEN PURWAKARTA'),
('3215', '32', 'KABUPATEN KARAWANG'),
('3216', '32', 'KABUPATEN BEKASI'),
('3217', '32', 'KABUPATEN BANDUNG BARAT'),
('3218', '32', 'KABUPATEN PANGANDARAN'),
('3271', '32', 'KOTA BOGOR'),
('3272', '32', 'KOTA SUKABUMI'),
('3273', '32', 'KOTA BANDUNG'),
('3274', '32', 'KOTA CIREBON'),
('3275', '32', 'KOTA BEKASI'),
('3276', '32', 'KOTA DEPOK'),
('3277', '32', 'KOTA CIMAHI'),
('3278', '32', 'KOTA TASIKMALAYA'),
('3279', '32', 'KOTA BANJAR'),
('3301', '33', 'KABUPATEN CILACAP'),
('3302', '33', 'KABUPATEN BANYUMAS'),
('3303', '33', 'KABUPATEN PURBALINGGA'),
('3304', '33', 'KABUPATEN BANJARNEGARA'),
('3305', '33', 'KABUPATEN KEBUMEN'),
('3306', '33', 'KABUPATEN PURWOREJO'),
('3307', '33', 'KABUPATEN WONOSOBO'),
('3308', '33', 'KABUPATEN MAGELANG'),
('3309', '33', 'KABUPATEN BOYOLALI'),
('3310', '33', 'KABUPATEN KLATEN'),
('3311', '33', 'KABUPATEN SUKOHARJO'),
('3312', '33', 'KABUPATEN WONOGIRI'),
('3313', '33', 'KABUPATEN KARANGANYAR'),
('3314', '33', 'KABUPATEN SRAGEN'),
('3315', '33', 'KABUPATEN GROBOGAN'),
('3316', '33', 'KABUPATEN BLORA'),
('3317', '33', 'KABUPATEN REMBANG'),
('3318', '33', 'KABUPATEN PATI'),
('3319', '33', 'KABUPATEN KUDUS'),
('3320', '33', 'KABUPATEN JEPARA'),
('3321', '33', 'KABUPATEN DEMAK'),
('3322', '33', 'KABUPATEN SEMARANG'),
('3323', '33', 'KABUPATEN TEMANGGUNG'),
('3324', '33', 'KABUPATEN KENDAL'),
('3325', '33', 'KABUPATEN BATANG'),
('3326', '33', 'KABUPATEN PEKALONGAN'),
('3327', '33', 'KABUPATEN PEMALANG'),
('3328', '33', 'KABUPATEN TEGAL'),
('3329', '33', 'KABUPATEN BREBES'),
('3371', '33', 'KOTA MAGELANG'),
('3372', '33', 'KOTA SURAKARTA'),
('3373', '33', 'KOTA SALATIGA'),
('3374', '33', 'KOTA SEMARANG'),
('3375', '33', 'KOTA PEKALONGAN'),
('3376', '33', 'KOTA TEGAL'),
('3401', '34', 'KABUPATEN KULON PROGO'),
('3402', '34', 'KABUPATEN BANTUL'),
('3403', '34', 'KABUPATEN GUNUNG KIDUL'),
('3404', '34', 'KABUPATEN SLEMAN'),
('3471', '34', 'KOTA YOGYAKARTA'),
('3501', '35', 'KABUPATEN PACITAN'),
('3502', '35', 'KABUPATEN PONOROGO'),
('3503', '35', 'KABUPATEN TRENGGALEK'),
('3504', '35', 'KABUPATEN TULUNGAGUNG'),
('3505', '35', 'KABUPATEN BLITAR'),
('3506', '35', 'KABUPATEN KEDIRI'),
('3507', '35', 'KABUPATEN MALANG'),
('3508', '35', 'KABUPATEN LUMAJANG'),
('3509', '35', 'KABUPATEN JEMBER'),
('3510', '35', 'KABUPATEN BANYUWANGI'),
('3511', '35', 'KABUPATEN BONDOWOSO'),
('3512', '35', 'KABUPATEN SITUBONDO'),
('3513', '35', 'KABUPATEN PROBOLINGGO'),
('3514', '35', 'KABUPATEN PASURUAN'),
('3515', '35', 'KABUPATEN SIDOARJO'),
('3516', '35', 'KABUPATEN MOJOKERTO'),
('3517', '35', 'KABUPATEN JOMBANG'),
('3518', '35', 'KABUPATEN NGANJUK'),
('3519', '35', 'KABUPATEN MADIUN'),
('3520', '35', 'KABUPATEN MAGETAN'),
('3521', '35', 'KABUPATEN NGAWI'),
('3522', '35', 'KABUPATEN BOJONEGORO'),
('3523', '35', 'KABUPATEN TUBAN'),
('3524', '35', 'KABUPATEN LAMONGAN'),
('3525', '35', 'KABUPATEN GRESIK'),
('3526', '35', 'KABUPATEN BANGKALAN'),
('3527', '35', 'KABUPATEN SAMPANG'),
('3528', '35', 'KABUPATEN PAMEKASAN'),
('3529', '35', 'KABUPATEN SUMENEP'),
('3571', '35', 'KOTA KEDIRI'),
('3572', '35', 'KOTA BLITAR'),
('3573', '35', 'KOTA MALANG'),
('3574', '35', 'KOTA PROBOLINGGO'),
('3575', '35', 'KOTA PASURUAN'),
('3576', '35', 'KOTA MOJOKERTO'),
('3577', '35', 'KOTA MADIUN'),
('3578', '35', 'KOTA SURABAYA'),
('3579', '35', 'KOTA BATU'),
('3601', '36', 'KABUPATEN PANDEGLANG'),
('3602', '36', 'KABUPATEN LEBAK'),
('3603', '36', 'KABUPATEN TANGERANG'),
('3604', '36', 'KABUPATEN SERANG'),
('3671', '36', 'KOTA TANGERANG'),
('3672', '36', 'KOTA CILEGON'),
('3673', '36', 'KOTA SERANG'),
('3674', '36', 'KOTA TANGERANG SELATAN'),
('5101', '51', 'KABUPATEN JEMBRANA'),
('5102', '51', 'KABUPATEN TABANAN'),
('5103', '51', 'KABUPATEN BADUNG'),
('5104', '51', 'KABUPATEN GIANYAR'),
('5105', '51', 'KABUPATEN KLUNGKUNG'),
('5106', '51', 'KABUPATEN BANGLI'),
('5107', '51', 'KABUPATEN KARANG ASEM'),
('5108', '51', 'KABUPATEN BULELENG'),
('5171', '51', 'KOTA DENPASAR'),
('5201', '52', 'KABUPATEN LOMBOK BARAT'),
('5202', '52', 'KABUPATEN LOMBOK TENGAH'),
('5203', '52', 'KABUPATEN LOMBOK TIMUR'),
('5204', '52', 'KABUPATEN SUMBAWA'),
('5205', '52', 'KABUPATEN DOMPU'),
('5206', '52', 'KABUPATEN BIMA'),
('5207', '52', 'KABUPATEN SUMBAWA BARAT'),
('5208', '52', 'KABUPATEN LOMBOK UTARA'),
('5271', '52', 'KOTA MATARAM'),
('5272', '52', 'KOTA BIMA'),
('5301', '53', 'KABUPATEN SUMBA BARAT'),
('5302', '53', 'KABUPATEN SUMBA TIMUR'),
('5303', '53', 'KABUPATEN KUPANG'),
('5304', '53', 'KABUPATEN TIMOR TENGAH SELATAN'),
('5305', '53', 'KABUPATEN TIMOR TENGAH UTARA'),
('5306', '53', 'KABUPATEN BELU'),
('5307', '53', 'KABUPATEN ALOR'),
('5308', '53', 'KABUPATEN LEMBATA'),
('5309', '53', 'KABUPATEN FLORES TIMUR'),
('5310', '53', 'KABUPATEN SIKKA'),
('5311', '53', 'KABUPATEN ENDE'),
('5312', '53', 'KABUPATEN NGADA'),
('5313', '53', 'KABUPATEN MANGGARAI'),
('5314', '53', 'KABUPATEN ROTE NDAO'),
('5315', '53', 'KABUPATEN MANGGARAI BARAT'),
('5316', '53', 'KABUPATEN SUMBA TENGAH'),
('5317', '53', 'KABUPATEN SUMBA BARAT DAYA'),
('5318', '53', 'KABUPATEN NAGEKEO'),
('5319', '53', 'KABUPATEN MANGGARAI TIMUR'),
('5320', '53', 'KABUPATEN SABU RAIJUA'),
('5321', '53', 'KABUPATEN MALAKA'),
('5371', '53', 'KOTA KUPANG'),
('6101', '61', 'KABUPATEN SAMBAS'),
('6102', '61', 'KABUPATEN BENGKAYANG'),
('6103', '61', 'KABUPATEN LANDAK'),
('6104', '61', 'KABUPATEN MEMPAWAH'),
('6105', '61', 'KABUPATEN SANGGAU'),
('6106', '61', 'KABUPATEN KETAPANG'),
('6107', '61', 'KABUPATEN SINTANG'),
('6108', '61', 'KABUPATEN KAPUAS HULU'),
('6109', '61', 'KABUPATEN SEKADAU'),
('6110', '61', 'KABUPATEN MELAWI'),
('6111', '61', 'KABUPATEN KAYONG UTARA'),
('6112', '61', 'KABUPATEN KUBU RAYA'),
('6171', '61', 'KOTA PONTIANAK'),
('6172', '61', 'KOTA SINGKAWANG'),
('6201', '62', 'KABUPATEN KOTAWARINGIN BARAT'),
('6202', '62', 'KABUPATEN KOTAWARINGIN TIMUR'),
('6203', '62', 'KABUPATEN KAPUAS'),
('6204', '62', 'KABUPATEN BARITO SELATAN'),
('6205', '62', 'KABUPATEN BARITO UTARA'),
('6206', '62', 'KABUPATEN SUKAMARA'),
('6207', '62', 'KABUPATEN LAMANDAU'),
('6208', '62', 'KABUPATEN SERUYAN'),
('6209', '62', 'KABUPATEN KATINGAN'),
('6210', '62', 'KABUPATEN PULANG PISAU'),
('6211', '62', 'KABUPATEN GUNUNG MAS'),
('6212', '62', 'KABUPATEN BARITO TIMUR'),
('6213', '62', 'KABUPATEN MURUNG RAYA'),
('6271', '62', 'KOTA PALANGKA RAYA'),
('6301', '63', 'KABUPATEN TANAH LAUT'),
('6302', '63', 'KABUPATEN KOTA BARU'),
('6303', '63', 'KABUPATEN BANJAR'),
('6304', '63', 'KABUPATEN BARITO KUALA'),
('6305', '63', 'KABUPATEN TAPIN'),
('6306', '63', 'KABUPATEN HULU SUNGAI SELATAN'),
('6307', '63', 'KABUPATEN HULU SUNGAI TENGAH'),
('6308', '63', 'KABUPATEN HULU SUNGAI UTARA'),
('6309', '63', 'KABUPATEN TABALONG'),
('6310', '63', 'KABUPATEN TANAH BUMBU'),
('6311', '63', 'KABUPATEN BALANGAN'),
('6371', '63', 'KOTA BANJARMASIN'),
('6372', '63', 'KOTA BANJAR BARU'),
('6401', '64', 'KABUPATEN PASER'),
('6402', '64', 'KABUPATEN KUTAI BARAT'),
('6403', '64', 'KABUPATEN KUTAI KARTANEGARA'),
('6404', '64', 'KABUPATEN KUTAI TIMUR'),
('6405', '64', 'KABUPATEN BERAU'),
('6409', '64', 'KABUPATEN PENAJAM PASER UTARA'),
('6411', '64', 'KABUPATEN MAHAKAM HULU'),
('6471', '64', 'KOTA BALIKPAPAN'),
('6472', '64', 'KOTA SAMARINDA'),
('6474', '64', 'KOTA BONTANG'),
('6501', '65', 'KABUPATEN MALINAU'),
('6502', '65', 'KABUPATEN BULUNGAN'),
('6503', '65', 'KABUPATEN TANA TIDUNG'),
('6504', '65', 'KABUPATEN NUNUKAN'),
('6571', '65', 'KOTA TARAKAN'),
('7101', '71', 'KABUPATEN BOLAANG MONGONDOW'),
('7102', '71', 'KABUPATEN MINAHASA'),
('7103', '71', 'KABUPATEN KEPULAUAN SANGIHE'),
('7104', '71', 'KABUPATEN KEPULAUAN TALAUD'),
('7105', '71', 'KABUPATEN MINAHASA SELATAN'),
('7106', '71', 'KABUPATEN MINAHASA UTARA'),
('7107', '71', 'KABUPATEN BOLAANG MONGONDOW UTARA'),
('7108', '71', 'KABUPATEN SIAU TAGULANDANG BIARO'),
('7109', '71', 'KABUPATEN MINAHASA TENGGARA'),
('7110', '71', 'KABUPATEN BOLAANG MONGONDOW SELATAN'),
('7111', '71', 'KABUPATEN BOLAANG MONGONDOW TIMUR'),
('7171', '71', 'KOTA MANADO'),
('7172', '71', 'KOTA BITUNG'),
('7173', '71', 'KOTA TOMOHON'),
('7174', '71', 'KOTA KOTAMOBAGU'),
('7201', '72', 'KABUPATEN BANGGAI KEPULAUAN'),
('7202', '72', 'KABUPATEN BANGGAI'),
('7203', '72', 'KABUPATEN MOROWALI'),
('7204', '72', 'KABUPATEN POSO'),
('7205', '72', 'KABUPATEN DONGGALA'),
('7206', '72', 'KABUPATEN TOLI-TOLI'),
('7207', '72', 'KABUPATEN BUOL'),
('7208', '72', 'KABUPATEN PARIGI MOUTONG'),
('7209', '72', 'KABUPATEN TOJO UNA-UNA'),
('7210', '72', 'KABUPATEN SIGI'),
('7211', '72', 'KABUPATEN BANGGAI LAUT'),
('7212', '72', 'KABUPATEN MOROWALI UTARA'),
('7271', '72', 'KOTA PALU'),
('7301', '73', 'KABUPATEN KEPULAUAN SELAYAR'),
('7302', '73', 'KABUPATEN BULUKUMBA'),
('7303', '73', 'KABUPATEN BANTAENG'),
('7304', '73', 'KABUPATEN JENEPONTO'),
('7305', '73', 'KABUPATEN TAKALAR'),
('7306', '73', 'KABUPATEN GOWA'),
('7307', '73', 'KABUPATEN SINJAI'),
('7308', '73', 'KABUPATEN MAROS'),
('7309', '73', 'KABUPATEN PANGKAJENE DAN KEPULAUAN'),
('7310', '73', 'KABUPATEN BARRU'),
('7311', '73', 'KABUPATEN BONE'),
('7312', '73', 'KABUPATEN SOPPENG'),
('7313', '73', 'KABUPATEN WAJO'),
('7314', '73', 'KABUPATEN SIDENRENG RAPPANG'),
('7315', '73', 'KABUPATEN PINRANG'),
('7316', '73', 'KABUPATEN ENREKANG'),
('7317', '73', 'KABUPATEN LUWU'),
('7318', '73', 'KABUPATEN TANA TORAJA'),
('7322', '73', 'KABUPATEN LUWU UTARA'),
('7325', '73', 'KABUPATEN LUWU TIMUR'),
('7326', '73', 'KABUPATEN TORAJA UTARA'),
('7371', '73', 'KOTA MAKASSAR'),
('7372', '73', 'KOTA PAREPARE'),
('7373', '73', 'KOTA PALOPO'),
('7401', '74', 'KABUPATEN BUTON'),
('7402', '74', 'KABUPATEN MUNA'),
('7403', '74', 'KABUPATEN KONAWE'),
('7404', '74', 'KABUPATEN KOLAKA'),
('7405', '74', 'KABUPATEN KONAWE SELATAN'),
('7406', '74', 'KABUPATEN BOMBANA'),
('7407', '74', 'KABUPATEN WAKATOBI'),
('7408', '74', 'KABUPATEN KOLAKA UTARA'),
('7409', '74', 'KABUPATEN BUTON UTARA'),
('7410', '74', 'KABUPATEN KONAWE UTARA'),
('7411', '74', 'KABUPATEN KOLAKA TIMUR'),
('7412', '74', 'KABUPATEN KONAWE KEPULAUAN'),
('7413', '74', 'KABUPATEN MUNA BARAT'),
('7414', '74', 'KABUPATEN BUTON TENGAH'),
('7415', '74', 'KABUPATEN BUTON SELATAN'),
('7471', '74', 'KOTA KENDARI'),
('7472', '74', 'KOTA BAUBAU'),
('7501', '75', 'KABUPATEN BOALEMO'),
('7502', '75', 'KABUPATEN GORONTALO'),
('7503', '75', 'KABUPATEN POHUWATO'),
('7504', '75', 'KABUPATEN BONE BOLANGO'),
('7505', '75', 'KABUPATEN GORONTALO UTARA'),
('7571', '75', 'KOTA GORONTALO'),
('7601', '76', 'KABUPATEN MAJENE'),
('7602', '76', 'KABUPATEN POLEWALI MANDAR'),
('7603', '76', 'KABUPATEN MAMASA'),
('7604', '76', 'KABUPATEN MAMUJU'),
('7605', '76', 'KABUPATEN MAMUJU UTARA'),
('7606', '76', 'KABUPATEN MAMUJU TENGAH'),
('8101', '81', 'KABUPATEN MALUKU TENGGARA BARAT'),
('8102', '81', 'KABUPATEN MALUKU TENGGARA'),
('8103', '81', 'KABUPATEN MALUKU TENGAH'),
('8104', '81', 'KABUPATEN BURU'),
('8105', '81', 'KABUPATEN KEPULAUAN ARU'),
('8106', '81', 'KABUPATEN SERAM BAGIAN BARAT'),
('8107', '81', 'KABUPATEN SERAM BAGIAN TIMUR'),
('8108', '81', 'KABUPATEN MALUKU BARAT DAYA'),
('8109', '81', 'KABUPATEN BURU SELATAN'),
('8171', '81', 'KOTA AMBON'),
('8172', '81', 'KOTA TUAL'),
('8201', '82', 'KABUPATEN HALMAHERA BARAT'),
('8202', '82', 'KABUPATEN HALMAHERA TENGAH'),
('8203', '82', 'KABUPATEN KEPULAUAN SULA'),
('8204', '82', 'KABUPATEN HALMAHERA SELATAN'),
('8205', '82', 'KABUPATEN HALMAHERA UTARA'),
('8206', '82', 'KABUPATEN HALMAHERA TIMUR'),
('8207', '82', 'KABUPATEN PULAU MOROTAI'),
('8208', '82', 'KABUPATEN PULAU TALIABU'),
('8271', '82', 'KOTA TERNATE'),
('8272', '82', 'KOTA TIDORE KEPULAUAN'),
('9101', '91', 'KABUPATEN FAKFAK'),
('9102', '91', 'KABUPATEN KAIMANA'),
('9103', '91', 'KABUPATEN TELUK WONDAMA'),
('9104', '91', 'KABUPATEN TELUK BINTUNI'),
('9105', '91', 'KABUPATEN MANOKWARI'),
('9106', '91', 'KABUPATEN SORONG SELATAN'),
('9107', '91', 'KABUPATEN SORONG'),
('9108', '91', 'KABUPATEN RAJA AMPAT'),
('9109', '91', 'KABUPATEN TAMBRAUW'),
('9110', '91', 'KABUPATEN MAYBRAT'),
('9111', '91', 'KABUPATEN MANOKWARI SELATAN'),
('9112', '91', 'KABUPATEN PEGUNUNGAN ARFAK'),
('9171', '91', 'KOTA SORONG'),
('9401', '94', 'KABUPATEN MERAUKE'),
('9402', '94', 'KABUPATEN JAYAWIJAYA'),
('9403', '94', 'KABUPATEN JAYAPURA'),
('9404', '94', 'KABUPATEN NABIRE'),
('9408', '94', 'KABUPATEN KEPULAUAN YAPEN'),
('9409', '94', 'KABUPATEN BIAK NUMFOR'),
('9410', '94', 'KABUPATEN PANIAI'),
('9411', '94', 'KABUPATEN PUNCAK JAYA'),
('9412', '94', 'KABUPATEN MIMIKA'),
('9413', '94', 'KABUPATEN BOVEN DIGOEL'),
('9414', '94', 'KABUPATEN MAPPI'),
('9415', '94', 'KABUPATEN ASMAT'),
('9416', '94', 'KABUPATEN YAHUKIMO'),
('9417', '94', 'KABUPATEN PEGUNUNGAN BINTANG'),
('9418', '94', 'KABUPATEN TOLIKARA'),
('9419', '94', 'KABUPATEN SARMI'),
('9420', '94', 'KABUPATEN KEEROM'),
('9426', '94', 'KABUPATEN WAROPEN'),
('9427', '94', 'KABUPATEN SUPIORI'),
('9428', '94', 'KABUPATEN MAMBERAMO RAYA'),
('9429', '94', 'KABUPATEN NDUGA'),
('9430', '94', 'KABUPATEN LANNY JAYA'),
('9431', '94', 'KABUPATEN MAMBERAMO TENGAH'),
('9432', '94', 'KABUPATEN YALIMO'),
('9433', '94', 'KABUPATEN PUNCAK'),
('9434', '94', 'KABUPATEN DOGIYAI'),
('9435', '94', 'KABUPATEN INTAN JAYA'),
('9436', '94', 'KABUPATEN DEIYAI'),
('9471', '94', 'KOTA JAYAPURA');

-- --------------------------------------------------------

--
-- Table structure for table `trx_log_akun`
--

CREATE TABLE `trx_log_akun` (
  `id` int(11) NOT NULL,
  `kode` varchar(165) NOT NULL,
  `keterangan` varchar(125) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trx_log_akun`
--

INSERT INTO `trx_log_akun` (`id`, `kode`, `keterangan`, `created_at`) VALUES
(1, '51EN71cEB0KQo', 'Menghapus SKPD Dinas Kependidikan dan kebudayaan', '2021-01-24 13:03:51'),
(2, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan KABID PENGELOLAAN INFOKOM PUBLIK', '2021-01-24 13:14:23'),
(3, '51EN71cEB0KQo', 'Menambah IKP', '2021-01-24 13:15:10'),
(4, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan KABID PENGELOLAAN INFOKOM PUBLIK', '2021-01-24 13:15:46'),
(5, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan admin (QC)', '2021-01-24 13:16:07'),
(6, '51EN71cEB0KQo', 'Menambah Detail JabatanKABID LAYANAN E-GOVERNMENT', '2021-01-24 13:19:19'),
(7, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI INSFRASTRUKTUR JARINGAN', '2021-01-24 13:19:46'),
(8, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI PENGELOLAAN KOMUNIKASI PUBLIK', '2021-01-24 13:20:12'),
(9, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI PENGELOLAAN INFORMASI PUBLIK', '2021-01-24 13:40:21'),
(10, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI LAYANAN INFORMASI DAN HUBUNGAN MEDIA', '2021-01-24 13:41:30'),
(11, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI PENGELOLAAN DATA STATISTIK SEKTOR I', '2021-01-24 13:41:51'),
(12, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI LAYANAN E-GOVERNMENT DAN PERSANDIAN', '2021-01-24 13:42:22'),
(13, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI PENGEMBANGAN & PENGELOLAAN APLIKASI', '2021-01-24 13:42:44'),
(14, '51EN71cEB0KQo', 'Menambah Statistik', '2021-01-24 13:43:17'),
(15, '51EN71cEB0KQo', 'Menambah Detail JabatanKASI PENGELOLAAN DATA STATISTIK SEKTOR II', '2021-01-24 13:43:33'),
(16, '51EN71cEB0KQo', 'Menambah Detail JabatanANALIS SISTEM INFORMASI', '2021-01-24 13:44:38'),
(17, '51EN71cEB0KQo', 'Menambah Detail JabatanPENYUSUN PROGRAM ANGGARAN DAN KEUANGAN', '2021-01-24 13:45:02'),
(18, '51EN71cEB0KQo', 'Registrasi SPV Baru KOKOH ARIE HIDAYAT, SE. S.Sos, MM		', '2021-01-24 13:50:47'),
(19, '51EN71cEB0KQo', 'Menambah Detail JabatanSekretaris 1', '2021-01-24 13:56:31'),
(20, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan Sekretaris 1', '2021-01-24 14:03:07'),
(21, '51EN71cEB0KQo', 'Registrasi SPV Baru Drs. SUGENG WINARTO, MM		', '2021-01-24 14:04:40'),
(22, '51EN71cEB0KQo', 'Menambah Test', '2021-01-24 14:30:08'),
(23, '51EN71cEB0KQo', 'Menghapus Bidang Test', '2021-01-24 14:30:20'),
(24, '51EN71cEB0KQo', 'Registrasi SPV Baru GATOT BUDIONO.SE		', '2021-01-24 14:31:39'),
(25, '51EN71cEB0KQo', 'Registrasi SPV Baru TATARINI WULANDARI,S.Kom		', '2021-01-24 14:33:43'),
(26, '51EN71cEB0KQo', 'Registrasi SPV Baru TUTUT WINARTONO, SH		', '2021-01-24 14:37:56'),
(27, '51EN71cEB0KQo', 'Perubahan Identitas Akun SPV Username tutut', '2021-01-25 01:04:43'),
(28, '51EN71cEB0KQo', 'Registrasi SPV Baru H.DAMAR TJATUR RAHARDJO, SH		', '2021-01-25 01:05:51'),
(29, '51EN71cEB0KQo', 'Registrasi SPV Baru PURBO SETIYONO,S Kom		', '2021-01-25 01:06:36'),
(30, '51EN71cEB0KQo', 'Registrasi SPV Baru HARTONO MEIZAL F.SE		', '2021-01-25 01:15:03'),
(31, '51EN71cEB0KQo', 'Registrasi SPV Baru AGUS SUSILO HADI S,Kom.MM		', '2021-01-25 01:15:43'),
(32, '51EN71cEB0KQo', 'Registrasi SPV Baru EKO SETYO BUDI, S.Tr		', '2021-01-25 01:16:20'),
(33, '51EN71cEB0KQo', 'Registrasi SPV Baru MOCH,CHUNAIVI S. S.Kom		', '2021-01-25 01:16:57'),
(34, '51EN71cEB0KQo', 'Registrasi SPV Baru BAYU WIKA PERMANA, S.AB		', '2021-01-25 01:18:03'),
(35, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan KASI PENGELOLAAN DATA STATISTIK SEKTOR I', '2021-01-25 01:18:33'),
(36, '51EN71cEB0KQo', 'Perubahan Identitas Akun SPV Username agus', '2021-01-25 01:20:26'),
(37, '51EN71cEB0KQo', 'Registrasi SPV (admin) Baru adminkominfo', '2021-01-25 01:21:11'),
(38, '51EN71cEB0KQo', 'Menghapus Username Adminkominfo', '2021-01-25 01:23:38'),
(39, '51EN71cEB0KQo', 'Registrasi SPV (admin) Baru admin kominfo', '2021-01-25 01:24:19'),
(40, '51EN71cEB0KQo', 'Update data admin 74ue6XJO7Goyw', '2021-01-25 01:39:07'),
(41, '51EN71cEB0KQo', 'Menambahkan Profesi Jaringan', '2021-01-25 01:39:35'),
(42, '51EN71cEB0KQo', 'Terjadi Perubahan Data Pada Profesi Jaringan', '2021-01-25 01:39:56'),
(43, '51EN71cEB0KQo', 'Menambahkan kegiatan Setting Mikrotik', '2021-01-25 01:40:26'),
(44, '51EN71cEB0KQo', 'Menambah Detail Jabatantestimo', '2021-01-25 01:45:52'),
(45, '51EN71cEB0KQo', 'Registrasi SPV Baru test', '2021-01-25 01:47:32'),
(46, '51EN71cEB0KQo', 'Perubahan Status Username Test Menjadi Non-Aktif', '2021-01-25 01:49:58'),
(47, '51EN71cEB0KQo', 'Menghapus Username Test', '2021-01-25 01:50:01'),
(48, '51EN71cEB0KQo', 'Registrasi SPV Baru test', '2021-01-25 01:51:01'),
(49, '51EN71cEB0KQo', 'Menghapus Username Test', '2021-01-25 01:52:10'),
(50, '51EN71cEB0KQo', 'Registrasi SPV Baru tes', '2021-01-25 01:54:45'),
(51, '51EN71cEB0KQo', 'Menghapus Username Username', '2021-01-25 02:11:42'),
(52, '51EN71cEB0KQo', 'Registrasi SPV Baru test', '2021-01-25 02:12:00'),
(53, '51EN71cEB0KQo', 'Menghapus Username Test', '2021-01-25 02:12:27'),
(54, '51EN71cEB0KQo', 'Registrasi THL Baru Zoe', '2021-01-25 02:14:32'),
(55, '51EN71cEB0KQo', 'Menambahkan kegiatan Buat Mockup', '2021-01-25 02:18:13'),
(56, '25gxnNBCg1zjA', 'Menambah Laporan Target Harian  Tanggal 25-01-2021', '2021-01-25 02:18:30'),
(57, '13dQYVMHAn6QQ', 'Verifikasi Laporan THL Zoe Tanggal 2021-01-25 Telah Disetujui', '2021-01-25 02:19:24'),
(58, '68jkRGBwU5g2w', 'Verifikasi Laporan THL Zoe Tanggal 2021-01-25 Telah Disetujui', '2021-01-25 02:20:10'),
(59, '25gxnNBCg1zjA', 'Menambah Laporan Aktivitas Harian Tanggal 01-01-1970', '2021-01-25 02:25:00'),
(60, '25gxnNBCg1zjA', 'Menambah Aktivitas Kegiatan Laporan Harian Tanggal 25-01-2021', '2021-01-25 02:29:28'),
(61, '25gxnNBCg1zjA', 'Menambah Aktivitas Kegiatan Laporan Harian Tanggal 25-01-2021', '2021-01-25 02:31:36'),
(62, '25gxnNBCg1zjA', 'Menghapus Aktivitas \"coding bagian login\" Harian Tanggal 25-01-2021', '2021-01-25 02:31:51'),
(63, '25gxnNBCg1zjA', 'Menghapus Aktivitas \"coding bagian login\" Harian Tanggal 25-01-2021', '2021-01-25 02:32:24'),
(64, '25gxnNBCg1zjA', 'Menambah Aktivitas Kegiatan Laporan Harian Tanggal 25-01-2021', '2021-01-25 02:33:30'),
(65, '13dQYVMHAn6QQ', 'Verifikasi Laporan Kegiatan THL Zoe Tanggal 25-01-2021 Telah Disetujui', '2021-01-25 02:34:50'),
(66, '68jkRGBwU5g2w', 'Verifikasi Laporan Kegiatan THL Zoe Tanggal 25-01-2021 Telah Disetujui', '2021-01-25 02:35:22'),
(67, '25gxnNBCg1zjA', 'Menambah Laporan Target Harian  Tanggal 17-01-2021', '2021-01-25 02:37:02'),
(68, '25gxnNBCg1zjA', 'Menambah Laporan Aktivitas Harian Tanggal 17-01-2021', '2021-01-25 02:40:18'),
(69, '13dQYVMHAn6QQ', 'Verifikasi Laporan Kegiatan THL Zoe Tanggal 17-01-2021 Telah Disetujui', '2021-01-25 02:44:15'),
(70, '68jkRGBwU5g2w', 'Verifikasi Laporan Kegiatan THL Zoe Tanggal 17-01-2021 Telah Disetujui', '2021-01-25 02:44:33'),
(71, '51EN71cEB0KQo', 'Registrasi SPV Baru Jaka Budiman (SpV)', '2021-01-25 02:58:30'),
(72, '51EN71cEB0KQo', 'Menghapus Username Test', '2021-01-25 02:58:39'),
(73, '25gxnNBCg1zjA', 'Menambah Laporan Target Harian  Tanggal 19-01-2021', '2021-01-25 03:47:27'),
(74, '13dQYVMHAn6QQ', 'Verifikasi Laporan THL Zoe Tanggal 2021-01-19 Telah Disetujui', '2021-01-25 03:49:02'),
(75, '68jkRGBwU5g2w', 'Verifikasi Laporan THL Zoe Tanggal 2021-01-19 Telah Disetujui', '2021-01-25 03:49:27'),
(76, '25gxnNBCg1zjA', 'Menambah Laporan Aktivitas Harian Tanggal 19-01-2021', '2021-01-25 03:51:27'),
(77, '51EN71cEB0KQo', 'Menambah SKPD Dinas Kependudukan dan Catatan Sipil', '2021-01-25 04:02:08'),
(78, '51EN71cEB0KQo', 'Menambah Sekretariat', '2021-01-25 04:03:34'),
(79, '51EN71cEB0KQo', 'Menambah Kependudukan', '2021-01-25 04:03:52'),
(80, '51EN71cEB0KQo', 'Menambah Pencatatan Sipil', '2021-01-25 04:04:12'),
(81, '51EN71cEB0KQo', 'Registrasi SPV (admin) Baru admin capil', '2021-01-25 04:05:24'),
(82, '51EN71cEB0KQo', 'Menghapus Username Admincapil', '2021-01-25 04:06:38'),
(83, '51EN71cEB0KQo', 'Registrasi SPV (admin) Baru admin capil', '2021-01-25 04:07:24'),
(84, '51EN71cEB0KQo', 'Registrasi SPV (admin) Baru admin capil', '2021-01-25 04:09:25'),
(85, '51EN71cEB0KQo', 'Update data admin 27gd1WfHMYa3U', '2021-01-25 04:09:46'),
(86, '51EN71cEB0KQo', 'Update data admin 27gd1WfHMYa3U', '2021-01-25 04:09:54'),
(87, '51EN71cEB0KQo', 'Update data admin 27gd1WfHMYa3U', '2021-01-25 04:10:13'),
(88, '51EN71cEB0KQo', 'Menghapus Username Admincapilee', '2021-01-25 04:10:28'),
(89, '51EN71cEB0KQo', 'Menambahkan Profesi Pengolah Berkas', '2021-01-25 04:11:04'),
(90, '51EN71cEB0KQo', 'Menambahkan kegiatan Entry Data', '2021-01-25 04:11:47'),
(91, '51EN71cEB0KQo', 'Mengubah Entry Data Menjadi Entry Dataa', '2021-01-25 04:12:02'),
(92, '51EN71cEB0KQo', 'Terjadi Perubahan Data Pada Kegiatan Entry Dataa', '2021-01-25 04:12:15'),
(93, '51EN71cEB0KQo', 'Mengubah Entry Dataa Menjadi Entry Data', '2021-01-25 04:12:41'),
(94, '51EN71cEB0KQo', 'Menghapus Kegiatan Entry Data', '2021-01-25 04:13:51'),
(95, '51EN71cEB0KQo', 'Menghapus Profesi Programmer', '2021-01-25 04:13:58'),
(96, '51EN71cEB0KQo', 'Menghapus Kegiatan Buat Mockup', '2021-01-25 04:15:11'),
(97, '51EN71cEB0KQo', 'Menambah Detail JabatanKepala Dinas Capil', '2021-01-25 04:17:40'),
(98, '51EN71cEB0KQo', 'Menambah Detail JabatanSekretaris', '2021-01-25 04:18:03'),
(99, '51EN71cEB0KQo', 'Menambah Detail JabatanKepegawaian', '2021-01-25 04:19:31'),
(100, '51EN71cEB0KQo', 'Menambah Detail JabatanKependudukan', '2021-01-25 04:20:01'),
(101, '51EN71cEB0KQo', 'Menambah Detail JabatanPencatatan Sipil', '2021-01-25 04:20:17'),
(102, '51EN71cEB0KQo', 'Menambah Detail JabatanKasi Kependudukan', '2021-01-25 04:20:38'),
(103, '51EN71cEB0KQo', 'Menambah Detail JabatanKasi Pencatatan Sipil', '2021-01-25 04:20:56'),
(104, '15rHYh0Vkt1k', 'Registrasi SPV Baru HJ. SITI MARIYAM, S.SOS, MM', '2021-01-25 04:25:26'),
(105, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan Kasubag Kepegawaian', '2021-01-25 04:26:19'),
(106, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan Kabid Kependudukan', '2021-01-25 04:26:31'),
(107, '51EN71cEB0KQo', 'Melakukan Perubahan data pada detail jabatan Kabid Pencatatan Sipil', '2021-01-25 04:26:43'),
(108, '15rHYh0Vkt1k', 'Registrasi SPV Baru SAROFAH', '2021-01-25 04:29:14'),
(109, '15rHYh0Vkt1k', 'Registrasi SPV Baru Ir. SUHARI, MM', '2021-01-25 04:31:17'),
(110, '51EN71cEB0KQo', 'Perubahan Status Username Sarofah_capil Menjadi Non-Aktif', '2021-01-25 04:31:59'),
(111, '51EN71cEB0KQo', 'Perubahan Status Username Sarofah_capil Menjadi Aktif', '2021-01-25 04:32:02'),
(112, '15rHYh0Vkt1k', 'Perubahan Identitas Akun SPV Username siti_mariyam', '2021-01-25 04:32:10'),
(113, '15rHYh0Vkt1k', 'Perubahan Identitas Akun SPV Username siti_mariyam', '2021-01-25 04:32:19'),
(114, '15rHYh0Vkt1k', 'Registrasi SPV Baru tes_hapus', '2021-01-25 04:33:08'),
(115, '15rHYh0Vkt1k', 'Menghapus Username 1234', '2021-01-25 04:33:14'),
(116, '15rHYh0Vkt1k', 'Registrasi SPV Baru tes_lagi', '2021-01-25 04:33:49'),
(117, '15rHYh0Vkt1k', 'Perubahan Status Username 1234 Menjadi Non-Aktif', '2021-01-25 04:33:54'),
(118, '15rHYh0Vkt1k', 'Perubahan Status Username 1234 Menjadi Aktif', '2021-01-25 04:33:57'),
(119, '15rHYh0Vkt1k', 'Melakukan Reset Password Username 1234', '2021-01-25 04:34:12'),
(120, '15rHYh0Vkt1k', 'Perubahan Identitas Akun SPV Username 1234', '2021-01-25 04:34:59'),
(121, '15rHYh0Vkt1k', 'Perubahan Identitas Akun SPV Username 1234', '2021-01-25 04:35:56'),
(122, '15rHYh0Vkt1k', 'Menghapus Username 1234', '2021-01-25 04:37:52'),
(123, '15rHYh0Vkt1k', 'Registrasi THL Baru Nama Lengkap', '2021-01-25 04:45:16'),
(124, '15rHYh0Vkt1k', 'Perubahan Status Username Thl_capil Menjadi Non-Aktif', '2021-01-25 04:45:25'),
(125, '15rHYh0Vkt1k', 'Perubahan Status Username Thl_capil Menjadi Aktif', '2021-01-25 04:45:35'),
(126, '34ca6T44cWOYA', 'Menambah Laporan Target Harian  Tanggal 20-01-2021', '2021-01-25 04:49:47'),
(127, '34ca6T44cWOYA', 'Menambah Laporan Target Harian  Tanggal 21-01-2021', '2021-01-25 04:50:19'),
(128, '34ca6T44cWOYA', 'Menambah Laporan Target Harian  Tanggal 14-01-2021', '2021-01-25 04:53:06'),
(129, '24gnSNa5fUUuA', 'Verifikasi Laporan THL Nama Lengkap Tanggal 2021-01-14 Telah Disetujui', '2021-01-25 04:54:44'),
(130, '24gnSNa5fUUuA', 'Verifikasi Laporan THL Nama Lengkap Tanggal 20-01-2021 Telah Ditolak', '2021-01-25 04:55:19'),
(131, '24gnSNa5fUUuA', 'Verifikasi Laporan THL Nama Lengkap Tanggal 2021-01-21 Telah Disetujui', '2021-01-25 04:55:28'),
(132, '915fdyAdIWuk', 'Verifikasi Laporan THL Nama Lengkap Tanggal 2021-01-14 Telah Disetujui', '2021-01-25 04:56:21'),
(133, '915fdyAdIWuk', 'Verifikasi Laporan THL Nama Lengkap Tanggal 21-01-2021 Telah Ditolak', '2021-01-25 04:56:50'),
(134, '51EN71cEB0KQo', 'Terjadi Perubahan Data Pada Kegiatan Setting Mikrotik', '2021-01-25 06:22:35'),
(135, '51EN71cEB0KQo', 'Menambahkan kegiatan Fix Bug', '2021-01-25 06:23:17'),
(136, '51EN71cEB0KQo', 'Menambahkan kegiatan Analisa', '2021-01-25 06:28:59'),
(137, '51EN71cEB0KQo', 'Menambahkan Profesi Videografer', '2021-01-25 06:33:59'),
(138, '34ca6T44cWOYA', 'Menambah Laporan Aktivitas Harian Tanggal 14-01-2021', '2021-01-25 06:43:48'),
(139, '24gnSNa5fUUuA', 'Verifikasi Laporan Kegiatan THL Nama Lengkap Tanggal 14-01-2021 Telah Disetujui', '2021-01-25 06:45:56'),
(140, '24gnSNa5fUUuA', 'Verifikasi Laporan Lainnya THL Nama Lengkap Tanggal 14-01-2021 Telah Disetujui', '2021-01-25 06:46:02'),
(141, '24gnSNa5fUUuA', 'Verifikasi Laporan Lainnya THL Nama Lengkap Tanggal 14-01-2021 Telah Ditolak', '2021-01-25 06:46:09'),
(142, '915fdyAdIWuk', 'Verifikasi Laporan Kegiatan THL Nama Lengkap Tanggal 14-01-2021 Telah Disetujui', '2021-01-25 06:46:20'),
(143, '915fdyAdIWuk', 'Verifikasi Laporan Lainnya THL Nama Lengkap Tanggal 14-01-2021 Telah Ditolak', '2021-01-25 06:46:24'),
(144, '51EN71cEB0KQo', 'Registrasi SPV (admin) Baru test', '2021-01-26 01:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `trx_thl_pengalaman_kerja`
--

CREATE TABLE `trx_thl_pengalaman_kerja` (
  `id` int(11) NOT NULL,
  `kode_thl` varchar(165) NOT NULL,
  `instansi` varchar(165) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `dokumen` varchar(165) DEFAULT NULL,
  `tgl_masuk` date NOT NULL,
  `tgl_keluar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trx_thl_pengalaman_kerja`
--

INSERT INTO `trx_thl_pengalaman_kerja` (`id`, `kode_thl`, `instansi`, `deskripsi`, `dokumen`, `tgl_masuk`, `tgl_keluar`) VALUES
(1, '34ca6T44cWOYA', 'Instansi 1', 'Pekerjaan ', NULL, '1913-12-12', '1914-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `trx_thl_sertfikat`
--

CREATE TABLE `trx_thl_sertfikat` (
  `id` int(11) NOT NULL,
  `kode_thl` varchar(165) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `dokumen` varchar(165) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trx_thl_sertifikat`
--

CREATE TABLE `trx_thl_sertifikat` (
  `id` int(11) NOT NULL,
  `kode_thl` varchar(165) NOT NULL,
  `deskripsi` varchar(165) NOT NULL,
  `tahun` char(4) NOT NULL,
  `dokumen` varchar(165) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trx_thl_sertifikat`
--

INSERT INTO `trx_thl_sertifikat` (`id`, `kode_thl`, `deskripsi`, `tahun`, `dokumen`) VALUES
(1, '34ca6T44cWOYA', 'Pelatihan 1', '1912', 'dok_sertifikat_1058tVvhBLY8M.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `t_akun`
--

CREATE TABLE `t_akun` (
  `id` int(11) NOT NULL,
  `kode` varchar(165) NOT NULL COMMENT 'FK kode_thl & kode_spv',
  `author_id` tinyint(4) NOT NULL,
  `stat_akun_id` tinyint(4) NOT NULL DEFAULT 1,
  `username` varchar(125) NOT NULL,
  `password` varchar(225) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_akun`
--

INSERT INTO `t_akun` (`id`, `kode`, `author_id`, `stat_akun_id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(6, '51EN71cEB0KQo', 1, 1, 'superadmin', '$2y$10$HflGbfIURziYKx8oQKme/.Av2VPHfN7HnGZl5wZmejSVyIughKXYm', '2020-12-03 07:36:06', '2021-01-24 13:04:41'),
(37, '626H9EwAsg36', 3, 1, 'kokoh', '$2a$08$GfDwDWd5JYS6BG.wD9FfW.dRz/8dXRY4eWummjr4q1M5rrE15wr2e', '2021-01-24 13:50:47', '2021-01-25 01:42:29'),
(38, '87Ln0nJ0u10U', 4, 1, 'sugeng', '$2a$08$p70X3J7R1I9Hqwo53bEK5ukVh533eWczizw7bWLf.2PdueCBM2yoq', '2021-01-24 14:04:40', '2021-01-25 01:42:54'),
(39, '66hjFySsheM', 4, 1, 'gatot', '$2a$08$KYGlPMwFNsDGvU/x5yYkEekYoDbQ23iq487HRB5qlUS.kfuhJfs72', '2021-01-24 14:31:39', '2021-01-25 01:42:57'),
(40, '68jkRGBwU5g2w', 4, 1, 'tatarini', '$2a$08$M.9nZuQnayODclEVQpFhc.e.RZVJLWllV/Av.VoGHgjxE45nlZ.Ee', '2021-01-24 14:33:43', '2021-01-25 03:37:03'),
(41, '176qOqc8frISs', 5, 1, 'tutut', '$2a$08$Zgtb1xCLIrlT0d0PcgjgseKLA3z9xNPznBTxaWZ8E5bCYaoUprJ2q', '2021-01-24 14:37:56', '2021-01-25 01:43:08'),
(42, '3345RwkTpj9V2', 5, 1, 'damar', '$2a$08$L8o1OSbZB/tr6zAA9Ibb.uAfErLpSfdRYHB06tVWMp9QPunICMF.C', '2021-01-25 01:05:51', '2021-01-25 01:43:10'),
(43, '19f3lA3fOjxjo', 5, 1, 'purbo', '$2a$08$2fFGfBEp95KBlBZGz01uv.IwG//EgeTH//t9sVJ78QkRTMNhc/E9.', '2021-01-25 01:06:36', '2021-01-25 01:43:12'),
(44, '92LDR76kbIE', 5, 1, 'hartono', '$2a$08$OJYUyJx4EyWQm4NRU3bo/usNw64IhZke7tXeOfiT.Tz9R6CLKMYUC', '2021-01-25 01:15:03', '2021-01-25 01:43:15'),
(45, '1058tVvhBLY8M', 5, 1, 'agus', '$2a$08$GUMwaSPxBg.xNytPcHraKe8Up40L6lNPc31M8H2NAaJrRzQXqe28.', '2021-01-25 01:15:43', '2021-01-25 01:43:18'),
(46, '12IQ1tEpS5R2', 5, 1, 'eko', '$2a$08$i8AFN.CMR7TsVijsTokRfuwxNqYTU0MLpH5bpi.MRradQW0JUoqXm', '2021-01-25 01:16:20', '2021-01-25 01:43:20'),
(48, '13dQYVMHAn6QQ', 5, 1, 'chunaivi', '$2a$08$Dmcf3NoTYI15GEUt44yzeuKsW0narO0nlN9HeoFFiIzi0fXkaLboS', '2021-01-25 01:16:57', '2021-01-25 01:43:22'),
(49, '11NbQyx3Aqk5g', 5, 1, 'bayu', '$2a$08$teJdama0a0BXdqcSSi1vTOBRJkLFCjLReDTafkde.Zju8W5vUlRnm', '2021-01-25 01:18:03', '2021-01-25 01:43:26'),
(52, '74ue6XJO7Goyw', 2, 1, 'adminkominfo', '$2a$08$ATtrakN0CsNa0RAjJjoyJe8F6ufG2niYe3JgeKN4L13AT8M1hxr92', '2021-01-25 01:24:19', NULL),
(58, '25gxnNBCg1zjA', 6, 1, 'zoe', '$2a$08$opDtibbz8Y3kQyIwcF3G5eOustxWDudD/CYYhGVdQyQDvWw9rDAdu', '2021-01-25 02:14:32', NULL),
(65, '15rHYh0Vkt1k', 2, 1, 'admincapil', '$2a$08$94/zDGVELHNSxZhOOPUmeOI2YP9Oy8Blg3dmLaqbClqwxCZ1hliOi', '2021-01-25 04:07:24', NULL),
(68, '915fdyAdIWuk', 4, 1, 'siti_mariyam', '$2a$08$fsMYJQfV.28T8XI10VULJ.shl85oAQsg0H0HE8rtz7ZLtyL3XnXg6', '2021-01-25 04:25:26', NULL),
(69, '24gnSNa5fUUuA', 5, 1, 'sarofah_capil', '$2a$08$XXKBv8hlOuiK2WyZkBEzg.2iykqkpnOYczfpqCim3P6zbdYzPsUmi', '2021-01-25 04:29:14', '2021-01-25 04:32:02'),
(70, '21iYbfmqZm0Ck', 3, 1, 'suhari_capil', '$2a$08$UOMR41FbVzhTPY8N0Usq.u/36ikppwQLddvYMi7dMJR4fG5ZNzg4u', '2021-01-25 04:31:17', NULL),
(73, '34ca6T44cWOYA', 6, 1, 'thl_capil', '$2a$08$pkNISWdnFwpU.4moVEAWxeg5Kaop0BRg7KriFp/Ckwmqs/TmQXD/S', '2021-01-25 04:45:16', '2021-01-25 04:45:35'),
(74, '19ZgXyZYic', 2, 1, 'tedtadmin', '$2a$08$GUyOc3DXAO//sKT7AGxCieEwMxgdj.E/z2Y2iIuWUNbC2..ERxtAi', '2021-01-26 01:04:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_laporan_kegiatan_thl`
--

CREATE TABLE `t_laporan_kegiatan_thl` (
  `id` smallint(6) NOT NULL,
  `kode_laporan_thl` varchar(165) NOT NULL,
  `kegiatan_thl_id` smallint(6) NOT NULL,
  `stat_laporan_id` tinyint(4) NOT NULL,
  `waktu` varchar(16) NOT NULL,
  `uraian` varchar(225) NOT NULL,
  `lampiran` varchar(165) NOT NULL,
  `kode_spv_kabid` varchar(16) NOT NULL,
  `kode_spv_kasie` varchar(16) NOT NULL,
  `tgl_verifikasi_kasie` timestamp NULL DEFAULT NULL,
  `tgl_verifikasi_kabid` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_laporan_kegiatan_thl`
--

INSERT INTO `t_laporan_kegiatan_thl` (`id`, `kode_laporan_thl`, `kegiatan_thl_id`, `stat_laporan_id`, `waktu`, `uraian`, `lampiran`, `kode_spv_kabid`, `kode_spv_kasie`, `tgl_verifikasi_kasie`, `tgl_verifikasi_kabid`) VALUES
(3, '1058tVvhBLY8M', 3, 3, '400', 'mackup login', '21iYbfmqZm0Ck.jpg', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2021-01-25 02:34:50', '2021-01-25 02:35:22'),
(4, '96j7uVoxNlPE', 1, 3, '200', 'coding login silat', '15rHYh0Vkt1k.jpg', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2021-01-25 02:44:15', '2021-01-25 02:44:33'),
(5, '386VXTnd4BsvQ', 1, 1, '200', 'coba', '11NbQyx3Aqk5g.pdf', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', NULL, NULL),
(6, '15rHYh0Vkt1k', 1, 3, '200', 'lorem asjdhakjdhkad klasjdlasjdl;aj kasjlkdjaljda asdada askdjalkjdlaj ;laskd;slakd;sad klasjdlajdla lkasjflajf lkasjdlasjdlsak lkasjdlasjdlsaj', '15rHYh0Vkt1k.pdf', '915fdyAdIWuk', '24gnSNa5fUUuA', '2021-01-25 06:45:56', '2021-01-25 06:46:20');

-- --------------------------------------------------------

--
-- Table structure for table `t_laporan_lain_thl`
--

CREATE TABLE `t_laporan_lain_thl` (
  `id` int(11) NOT NULL,
  `kode_laporan_thl` varchar(165) NOT NULL,
  `stat_laporan_id` tinyint(4) NOT NULL,
  `waktu` char(4) NOT NULL,
  `uraian` varchar(225) NOT NULL,
  `lampiran` varchar(165) NOT NULL,
  `kode_spv_kabid` varchar(16) NOT NULL,
  `kode_spv_kasie` varchar(16) NOT NULL,
  `tgl_verifikasi_kasie` timestamp NULL DEFAULT NULL,
  `tgl_verifikasi_kabid` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_laporan_lain_thl`
--

INSERT INTO `t_laporan_lain_thl` (`id`, `kode_laporan_thl`, `stat_laporan_id`, `waktu`, `uraian`, `lampiran`, `kode_spv_kabid`, `kode_spv_kasie`, `tgl_verifikasi_kasie`, `tgl_verifikasi_kabid`) VALUES
(1, '386VXTnd4BsvQ', 1, '100', 'tester coba', '72WR7VCspNM3E.pdf', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', NULL, NULL),
(2, '15rHYh0Vkt1k', 5, '100', 'aktvitias luar', '148ETLHYA0XQ.pdf', '915fdyAdIWuk', '24gnSNa5fUUuA', '2021-01-25 06:46:02', '2021-01-25 06:46:24'),
(3, '15rHYh0Vkt1k', 4, '200', 'aktivitas lain', '942jvNvjjBqAU.pdf', '915fdyAdIWuk', '24gnSNa5fUUuA', '2021-01-25 06:46:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_laporan_thl`
--

CREATE TABLE `t_laporan_thl` (
  `id` int(6) NOT NULL,
  `kode_laporan_thl` varchar(165) NOT NULL,
  `kode_target_thl` varchar(165) NOT NULL,
  `skpd_id` smallint(6) NOT NULL,
  `bidang_skpd_id` mediumint(9) NOT NULL,
  `profesi_thl_id` smallint(6) NOT NULL,
  `kode_thl` varchar(165) NOT NULL,
  `kode_spv_kabid` varchar(165) NOT NULL,
  `kode_spv_kasie` varchar(165) NOT NULL,
  `tgl_laporan` date NOT NULL,
  `jml_waktu` char(6) NOT NULL,
  `created_by` varchar(165) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_laporan_thl`
--

INSERT INTO `t_laporan_thl` (`id`, `kode_laporan_thl`, `kode_target_thl`, `skpd_id`, `bidang_skpd_id`, `profesi_thl_id`, `kode_thl`, `kode_spv_kabid`, `kode_spv_kasie`, `tgl_laporan`, `jml_waktu`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '1058tVvhBLY8M', '72WR7VCspNM3E', 1, 2, 1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2021-01-25', '1000', '25gxnNBCg1zjA', '2021-01-25 02:25:00', '2021-01-25 02:33:30'),
(2, '96j7uVoxNlPE', '12IQ1tEpS5R2', 1, 2, 1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2021-01-17', '200', '25gxnNBCg1zjA', '2021-01-25 02:40:17', NULL),
(3, '386VXTnd4BsvQ', '80QH7SSqs4h9w', 1, 2, 1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2021-01-19', '300', '25gxnNBCg1zjA', '2021-01-25 03:51:27', NULL),
(4, '15rHYh0Vkt1k', '53OZBacqadg92', 9, 22, 1, '34ca6T44cWOYA', '915fdyAdIWuk', '24gnSNa5fUUuA', '2021-01-14', '500', '34ca6T44cWOYA', '2021-01-25 06:43:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_spv`
--

CREATE TABLE `t_spv` (
  `id` smallint(6) NOT NULL,
  `kode_spv` varchar(165) NOT NULL,
  `skpd_id` smallint(6) NOT NULL,
  `bidang_skpd_id` mediumint(9) NOT NULL,
  `jabatan_spv_id` smallint(6) NOT NULL,
  `nip` varchar(165) NOT NULL,
  `nama` varchar(165) NOT NULL,
  `created_by` varchar(165) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_spv`
--

INSERT INTO `t_spv` (`id`, `kode_spv`, `skpd_id`, `bidang_skpd_id`, `jabatan_spv_id`, `nip`, `nama`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '51EN71cEB0KQo', 1, 1, 0, '12345467789123456', 'Superadmin', '51EN71cEB0KQo', '2020-12-02 03:44:37', '2021-01-24 13:26:44'),
(23, '626H9EwAsg36', 1, 1, 3, '197609191996021003', 'KOKOH ARIE HIDAYAT, SE. S.Sos, MM		', '51EN71cEB0KQo', '2021-01-24 13:50:47', NULL),
(24, '87Ln0nJ0u10U', 1, 1, 24, '196709121993021002', 'Drs. SUGENG WINARTO, MM		', '51EN71cEB0KQo', '2021-01-24 14:04:40', NULL),
(25, '66hjFySsheM', 1, 19, 12, '196805151990031007', 'GATOT BUDIONO.SE		', '51EN71cEB0KQo', '2021-01-24 14:31:39', NULL),
(26, '68jkRGBwU5g2w', 1, 2, 13, '197212141996032001', 'TATARINI WULANDARI,S.Kom		', '51EN71cEB0KQo', '2021-01-24 14:33:43', NULL),
(27, '176qOqc8frISs', 1, 2, 14, '196403151986031028', 'TUTUT WINARTONO, SH		', '51EN71cEB0KQo', '2021-01-24 14:37:56', '2021-01-25 01:04:43'),
(28, '3345RwkTpj9V2', 1, 19, 15, '196408282003121003', 'H.DAMAR TJATUR RAHARDJO, SH		', '51EN71cEB0KQo', '2021-01-25 01:05:51', NULL),
(29, '19f3lA3fOjxjo', 1, 19, 16, '197107172003121009', 'PURBO SETIYONO,S Kom		', '51EN71cEB0KQo', '2021-01-25 01:06:36', NULL),
(30, '92LDR76kbIE', 1, 19, 17, '197905252003121008', 'HARTONO MEIZAL F.SE		', '51EN71cEB0KQo', '2021-01-25 01:15:03', NULL),
(31, '1058tVvhBLY8M', 1, 20, 18, '197304062006041014', 'AGUS SUSILO HADI S,Kom.MM		', '51EN71cEB0KQo', '2021-01-25 01:15:43', '2021-01-25 01:20:26'),
(32, '12IQ1tEpS5R2', 1, 2, 19, '198105302009041001', 'EKO SETYO BUDI, S.Tr		', '51EN71cEB0KQo', '2021-01-25 01:16:20', NULL),
(34, '13dQYVMHAn6QQ', 1, 2, 20, '198305042010011020', 'MOCH,CHUNAIVI S. S.Kom		', '51EN71cEB0KQo', '2021-01-25 01:16:57', NULL),
(35, '11NbQyx3Aqk5g', 1, 20, 21, '198608162010011010', 'BAYU WIKA PERMANA, S.AB		', '51EN71cEB0KQo', '2021-01-25 01:18:03', NULL),
(38, '74ue6XJO7Goyw', 1, 2, 2, '12345678', 'admin kominfo', '51EN71cEB0KQo', '2021-01-25 01:24:19', '2021-01-25 01:39:07'),
(50, '15rHYh0Vkt1k', 9, 22, 2, '123131313', 'admin capil', '51EN71cEB0KQo', '2021-01-25 04:07:24', NULL),
(53, '915fdyAdIWuk', 9, 22, 27, '196703161989032012', 'HJ. SITI MARIYAM, S.SOS, MM', '15rHYh0Vkt1k', '2021-01-25 04:25:26', '2021-01-25 04:32:19'),
(54, '24gnSNa5fUUuA', 9, 22, 28, '196304161989102002', 'SAROFAH', '15rHYh0Vkt1k', '2021-01-25 04:29:14', NULL),
(55, '21iYbfmqZm0Ck', 9, 22, 26, '195901221983031004', 'Ir. SUHARI, MM', '15rHYh0Vkt1k', '2021-01-25 04:31:17', NULL),
(58, '19ZgXyZYic', 9, 23, 2, '232323', 'test', '51EN71cEB0KQo', '2021-01-26 01:04:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_target_detail_thl`
--

CREATE TABLE `t_target_detail_thl` (
  `id` int(11) NOT NULL,
  `kode_target_thl` varchar(165) NOT NULL,
  `kegiatan_thl_id` smallint(6) NOT NULL,
  `waktu` char(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_target_detail_thl`
--

INSERT INTO `t_target_detail_thl` (`id`, `kode_target_thl`, `kegiatan_thl_id`, `waktu`) VALUES
(1, '72WR7VCspNM3E', 1, '400'),
(2, '72WR7VCspNM3E', 3, '200'),
(3, '12IQ1tEpS5R2', 1, '402'),
(4, '80QH7SSqs4h9w', 1, '250'),
(5, '80QH7SSqs4h9w', 3, '150'),
(6, '16MyJTs0aqkrg', 1, '150'),
(7, '16MyJTs0aqkrg', 3, '180'),
(8, '20dh6dsFuM1tc', 3, '330'),
(9, '53OZBacqadg92', 1, '350');

-- --------------------------------------------------------

--
-- Table structure for table `t_target_thl`
--

CREATE TABLE `t_target_thl` (
  `id` int(11) NOT NULL,
  `kode_target_thl` varchar(165) NOT NULL,
  `stat_laporan_id` tinyint(4) NOT NULL DEFAULT 1,
  `skpd_id` smallint(6) NOT NULL,
  `bidang_skpd_id` mediumint(6) NOT NULL,
  `profesi_thl_id` smallint(6) NOT NULL,
  `kode_thl` varchar(165) NOT NULL,
  `kode_spv_kabid` varchar(165) NOT NULL,
  `kode_spv_kasie` varchar(165) NOT NULL,
  `jml_target` char(4) NOT NULL,
  `tgl_laporan` date NOT NULL,
  `jml_waktu` char(4) NOT NULL,
  `tgl_verifikasi_kasie` timestamp NULL DEFAULT NULL,
  `tgl_verifikasi_kabid` timestamp NULL DEFAULT NULL,
  `created_by` varchar(165) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_target_thl`
--

INSERT INTO `t_target_thl` (`id`, `kode_target_thl`, `stat_laporan_id`, `skpd_id`, `bidang_skpd_id`, `profesi_thl_id`, `kode_thl`, `kode_spv_kabid`, `kode_spv_kasie`, `jml_target`, `tgl_laporan`, `jml_waktu`, `tgl_verifikasi_kasie`, `tgl_verifikasi_kabid`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '72WR7VCspNM3E', 3, 1, 2, 1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2', '2021-01-25', '600', '2021-01-25 02:19:24', '2021-01-25 02:20:10', '25gxnNBCg1zjA', '2021-01-25 02:18:30', '2021-01-25 02:20:10'),
(2, '12IQ1tEpS5R2', 3, 1, 2, 1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '1', '2021-01-17', '402', NULL, NULL, '25gxnNBCg1zjA', '2021-01-25 02:37:02', '2021-01-25 02:37:45'),
(4, '80QH7SSqs4h9w', 3, 1, 2, 1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', '2', '2021-01-19', '400', '2021-01-25 03:49:02', '2021-01-25 03:49:27', '25gxnNBCg1zjA', '2021-01-25 03:47:27', '2021-01-25 03:49:27'),
(5, '16MyJTs0aqkrg', 4, 9, 22, 1, '34ca6T44cWOYA', '915fdyAdIWuk', '24gnSNa5fUUuA', '2', '2021-01-20', '330', '2021-01-25 04:55:19', NULL, '34ca6T44cWOYA', '2021-01-25 04:49:47', '2021-01-25 04:55:19'),
(6, '20dh6dsFuM1tc', 5, 9, 22, 1, '34ca6T44cWOYA', '915fdyAdIWuk', '24gnSNa5fUUuA', '1', '2021-01-21', '330', '2021-01-25 04:55:28', '2021-01-25 04:56:50', '34ca6T44cWOYA', '2021-01-25 04:50:19', '2021-01-25 04:56:50'),
(7, '53OZBacqadg92', 3, 9, 22, 1, '34ca6T44cWOYA', '915fdyAdIWuk', '24gnSNa5fUUuA', '1', '2021-01-14', '350', '2021-01-25 04:54:44', '2021-01-25 04:56:21', '34ca6T44cWOYA', '2021-01-25 04:53:06', '2021-01-25 04:56:21');

-- --------------------------------------------------------

--
-- Table structure for table `t_thl`
--

CREATE TABLE `t_thl` (
  `id` smallint(6) NOT NULL,
  `kode_thl` varchar(165) NOT NULL,
  `kode_spv_kabid` varchar(165) NOT NULL,
  `kode_spv_kasie` varchar(165) NOT NULL,
  `skpd_id` smallint(6) NOT NULL,
  `bidang_skpd_id` mediumint(6) NOT NULL,
  `profesi_thl_id` smallint(6) NOT NULL,
  `stat_perkawinan_id` tinyint(4) NOT NULL,
  `pendidikan_id` tinyint(4) NOT NULL,
  `nik` char(16) NOT NULL,
  `nama` varchar(165) NOT NULL,
  `tmpt_lahir` char(4) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `tmpt_asal` char(4) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `email` varchar(165) NOT NULL,
  `telepon` char(25) NOT NULL,
  `tmt_pendidikan` varchar(165) NOT NULL,
  `ijazah` varchar(165) NOT NULL,
  `created_by` varchar(165) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `t_thl`
--

INSERT INTO `t_thl` (`id`, `kode_thl`, `kode_spv_kabid`, `kode_spv_kasie`, `skpd_id`, `bidang_skpd_id`, `profesi_thl_id`, `stat_perkawinan_id`, `pendidikan_id`, `nik`, `nama`, `tmpt_lahir`, `tgl_lahir`, `tmpt_asal`, `alamat`, `email`, `telepon`, `tmt_pendidikan`, `ijazah`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '25gxnNBCg1zjA', '68jkRGBwU5g2w', '13dQYVMHAn6QQ', 1, 2, 1, 4, 8, '123', 'Zoe', '1101', '2021-01-26', '1102', '-', 'test@password.com', '076', '-', '25gxnNBCg1zjA.pdf', '51EN71cEB0KQo', '2021-01-25 02:14:32', NULL),
(2, '34ca6T44cWOYA', '915fdyAdIWuk', '24gnSNa5fUUuA', 9, 22, 1, 3, 10, '1234', 'Nama Lengkap', '9101', '1912-12-12', '9101', 'alamat', 'email@email', '081222', 'sekolah', '34ca6T44cWOYA.pdf', '15rHYh0Vkt1k', '2021-01-25 04:45:16', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_status_laporan_thl`
-- (See below for the actual view)
--
CREATE TABLE `v_status_laporan_thl` (
`kode_laporan` varchar(165)
,`jumlah` decimal(42,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_summary_laporan_thl`
-- (See below for the actual view)
--
CREATE TABLE `v_summary_laporan_thl` (
`nama_thl` varchar(165)
,`nama_kabid` varchar(165)
,`nama_kasie` varchar(165)
,`kode_thl` varchar(165)
,`kode_spv_kasie` varchar(165)
,`kode_spv_kabid` varchar(165)
,`skpd_id` smallint(6)
,`skpd` varchar(165)
,`bidang_skpd_id` mediumint(9)
,`bidang_skpd` varchar(165)
,`tgl_laporan` date
,`waktu` char(6)
,`kegiatan` mediumtext
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_summary_laporan_thl_new`
-- (See below for the actual view)
--
CREATE TABLE `v_summary_laporan_thl_new` (
`nama_thl` varchar(165)
,`nama_kabid` varchar(165)
,`nama_kasie` varchar(165)
,`kode_thl` varchar(165)
,`kode_spv_kasie` varchar(165)
,`kode_spv_kabid` varchar(165)
,`skpd_id` smallint(6)
,`skpd` varchar(165)
,`bidang_skpd_id` mediumint(9)
,`bidang_skpd` varchar(165)
,`tgl_laporan` date
,`waktu` char(6)
,`status_verifikasi` varchar(25)
);

-- --------------------------------------------------------

--
-- Structure for view `v_status_laporan_thl`
--
DROP TABLE IF EXISTS `v_status_laporan_thl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_status_laporan_thl`  AS  select `tmp`.`kode_laporan` AS `kode_laporan`,sum(`tmp`.`count_verifikasi`) AS `jumlah` from (select `t_laporan_thl`.`id` AS `laporan_id`,`t_laporan_thl`.`kode_laporan_thl` AS `kode_laporan`,`t_laporan_thl`.`kode_thl` AS `kode_thl`,cast(`t_laporan_thl`.`tgl_laporan` as date) AS `tgl_laporan`,`t_laporan_thl`.`jml_waktu` AS `waktu`,count(if(`t_laporan_kegiatan_thl`.`stat_laporan_id` = 1 or `t_laporan_kegiatan_thl`.`stat_laporan_id` = 2,1,NULL)) AS `count_verifikasi` from (`t_laporan_thl` left join `t_laporan_kegiatan_thl` on(`t_laporan_thl`.`kode_laporan_thl` = `t_laporan_kegiatan_thl`.`kode_laporan_thl`)) group by `t_laporan_thl`.`id`,`t_laporan_thl`.`kode_laporan_thl`,`t_laporan_thl`.`kode_thl`,`t_laporan_thl`.`jml_waktu` union all select `t_laporan_thl`.`id` AS `laporan_id`,`t_laporan_thl`.`kode_laporan_thl` AS `kode_laporan`,`t_laporan_thl`.`kode_thl` AS `kode_thl`,cast(`t_laporan_thl`.`tgl_laporan` as date) AS `tgl_laporan`,`t_laporan_thl`.`jml_waktu` AS `waktu`,count(if(`t_laporan_lain_thl`.`stat_laporan_id` = 1 or `t_laporan_lain_thl`.`stat_laporan_id` = 2,1,NULL)) AS `count_verifikasi` from (`t_laporan_thl` left join `t_laporan_lain_thl` on(`t_laporan_thl`.`kode_laporan_thl` = `t_laporan_lain_thl`.`kode_laporan_thl`)) group by `t_laporan_thl`.`id`,`t_laporan_thl`.`kode_laporan_thl`,`t_laporan_thl`.`kode_thl`,`t_laporan_thl`.`jml_waktu`) `tmp` group by `tmp`.`kode_laporan` ;

-- --------------------------------------------------------

--
-- Structure for view `v_summary_laporan_thl`
--
DROP TABLE IF EXISTS `v_summary_laporan_thl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_summary_laporan_thl`  AS  with summary_laporan(`laporan_id`,`kode_laporan_thl`,`kode_thl`,`kode_spv_kasie`,`kode_spv_kabid`,`skpd_id`,`bidang_skpd_id`,`tgl_laporan`,`waktu`,`kegiatan`) as (select `t_laporan_thl`.`id` AS `laporan_id`,`t_laporan_thl`.`kode_laporan_thl` AS `kode_laporan_thl`,`t_laporan_thl`.`kode_thl` AS `kode_thl`,`t_laporan_thl`.`kode_spv_kasie` AS `kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid` AS `kode_spv_kabid`,`t_laporan_thl`.`skpd_id` AS `skpd_id`,`t_laporan_thl`.`bidang_skpd_id` AS `bidang_skpd_id`,cast(`t_laporan_thl`.`tgl_laporan` as date) AS `tgl_laporan`,`t_laporan_thl`.`jml_waktu` AS `waktu`,group_concat(concat(`m_kegiatan_thl`.`deskripsi`,' (',`m_stat_laporan`.`deskripsi`,')') separator ', ') AS `kegiatan` from (((`t_laporan_thl` left join `t_laporan_kegiatan_thl` on(`t_laporan_kegiatan_thl`.`kode_laporan_thl` = `t_laporan_thl`.`kode_laporan_thl`)) left join `m_kegiatan_thl` on(`m_kegiatan_thl`.`id` = `t_laporan_kegiatan_thl`.`kegiatan_thl_id`)) left join `m_stat_laporan` on(`m_stat_laporan`.`id` = `t_laporan_kegiatan_thl`.`stat_laporan_id`)) group by `t_laporan_thl`.`id`,`t_laporan_thl`.`kode_laporan_thl`,`t_laporan_thl`.`kode_thl`,`t_laporan_thl`.`kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid`,`t_laporan_thl`.`skpd_id`,`t_laporan_thl`.`bidang_skpd_id`,`t_laporan_thl`.`jml_waktu` union all select `t_laporan_thl`.`id` AS `laporan_id`,`t_laporan_thl`.`kode_laporan_thl` AS `kode_laporan_thl`,`t_laporan_thl`.`kode_thl` AS `kode_thl`,`t_laporan_thl`.`kode_spv_kasie` AS `kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid` AS `kode_spv_kabid`,`t_laporan_thl`.`skpd_id` AS `skpd_id`,`t_laporan_thl`.`bidang_skpd_id` AS `bidang_skpd_id`,cast(`t_laporan_thl`.`tgl_laporan` as date) AS `tgl_laporan`,`t_laporan_thl`.`jml_waktu` AS `waktu`,group_concat(concat(`t_laporan_lain_thl`.`uraian`,' (',`m_stat_laporan`.`deskripsi`,')') separator ', ') AS `kegiatan` from ((`t_laporan_thl` left join `t_laporan_lain_thl` on(`t_laporan_lain_thl`.`kode_laporan_thl` = `t_laporan_thl`.`kode_laporan_thl`)) left join `m_stat_laporan` on(`m_stat_laporan`.`id` = `t_laporan_lain_thl`.`stat_laporan_id`)) group by `t_laporan_thl`.`id`,`t_laporan_thl`.`kode_laporan_thl`,`t_laporan_thl`.`kode_thl`,`t_laporan_thl`.`kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid`,`t_laporan_thl`.`skpd_id`,`t_laporan_thl`.`bidang_skpd_id`,`t_laporan_thl`.`jml_waktu`)select `t_thl`.`nama` AS `nama_thl`,`spv_kabid`.`nama` AS `nama_kabid`,`spv_kasie`.`nama` AS `nama_kasie`,`summary_laporan`.`kode_thl` AS `kode_thl`,`summary_laporan`.`kode_spv_kasie` AS `kode_spv_kasie`,`summary_laporan`.`kode_spv_kabid` AS `kode_spv_kabid`,`m_skpd`.`id` AS `skpd_id`,`m_skpd`.`deskripsi` AS `skpd`,`m_bidang_skpd`.`id` AS `bidang_skpd_id`,`m_bidang_skpd`.`deskripsi` AS `bidang_skpd`,`summary_laporan`.`tgl_laporan` AS `tgl_laporan`,`summary_laporan`.`waktu` AS `waktu`,group_concat(`summary_laporan`.`kegiatan` separator ', ') AS `kegiatan` from (((((`summary_laporan` join `t_thl` on(`t_thl`.`kode_thl` = `summary_laporan`.`kode_thl`)) join `m_skpd` on(`summary_laporan`.`skpd_id` = `m_skpd`.`id`)) join `m_bidang_skpd` on(`summary_laporan`.`bidang_skpd_id` = `m_bidang_skpd`.`id`)) left join `t_spv` `spv_kasie` on(`summary_laporan`.`kode_spv_kasie` = `spv_kasie`.`kode_spv`)) left join `t_spv` `spv_kabid` on(`summary_laporan`.`kode_spv_kabid` = `spv_kabid`.`kode_spv`)) group by `t_thl`.`nama`,`spv_kabid`.`nama`,`spv_kasie`.`nama`,`summary_laporan`.`kode_thl`,`summary_laporan`.`kode_spv_kasie`,`summary_laporan`.`kode_spv_kabid`,`m_skpd`.`id`,`m_skpd`.`deskripsi`,`m_bidang_skpd`.`id`,`m_bidang_skpd`.`deskripsi`,`summary_laporan`.`tgl_laporan`,`summary_laporan`.`waktu` ;

-- --------------------------------------------------------

--
-- Structure for view `v_summary_laporan_thl_new`
--
DROP TABLE IF EXISTS `v_summary_laporan_thl_new`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_summary_laporan_thl_new`  AS  with count_verifikasi(`laporan_id`,`kode_laporan`,`kode_thl`,`kode_spv_kasie`,`kode_spv_kabid`,`skpd_id`,`bidang_skpd_id`,`tgl_laporan`,`waktu`,`count_verifikasi`) as (select `t_laporan_thl`.`id` AS `laporan_id`,`t_laporan_thl`.`kode_laporan_thl` AS `kode_laporan`,`t_laporan_thl`.`kode_thl` AS `kode_thl`,`t_laporan_thl`.`kode_spv_kasie` AS `kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid` AS `kode_spv_kabid`,`t_laporan_thl`.`skpd_id` AS `skpd_id`,`t_laporan_thl`.`bidang_skpd_id` AS `bidang_skpd_id`,cast(`t_laporan_thl`.`tgl_laporan` as date) AS `tgl_laporan`,`t_laporan_thl`.`jml_waktu` AS `waktu`,count(if(`t_laporan_kegiatan_thl`.`stat_laporan_id` = 1 or `t_laporan_kegiatan_thl`.`stat_laporan_id` = 2,1,NULL)) AS `count_verifikasi` from (`t_laporan_thl` left join `t_laporan_kegiatan_thl` on(`t_laporan_thl`.`kode_laporan_thl` = `t_laporan_kegiatan_thl`.`kode_laporan_thl`)) group by `t_laporan_thl`.`id`,`t_laporan_thl`.`kode_laporan_thl`,`t_laporan_thl`.`kode_thl`,`t_laporan_thl`.`kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid`,`t_laporan_thl`.`skpd_id`,`t_laporan_thl`.`bidang_skpd_id`,`t_laporan_thl`.`jml_waktu` union all select `t_laporan_thl`.`id` AS `laporan_id`,`t_laporan_thl`.`kode_laporan_thl` AS `kode_laporan`,`t_laporan_thl`.`kode_thl` AS `kode_thl`,`t_laporan_thl`.`kode_spv_kasie` AS `kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid` AS `kode_spv_kabid`,`t_laporan_thl`.`skpd_id` AS `skpd_id`,`t_laporan_thl`.`bidang_skpd_id` AS `bidang_skpd_id`,cast(`t_laporan_thl`.`tgl_laporan` as date) AS `tgl_laporan`,`t_laporan_thl`.`jml_waktu` AS `waktu`,count(if(`t_laporan_lain_thl`.`stat_laporan_id` = 1 or `t_laporan_lain_thl`.`stat_laporan_id` = 2,1,NULL)) AS `count_verifikasi` from (`t_laporan_thl` left join `t_laporan_lain_thl` on(`t_laporan_thl`.`kode_laporan_thl` = `t_laporan_lain_thl`.`kode_laporan_thl`)) group by `t_laporan_thl`.`id`,`t_laporan_thl`.`kode_laporan_thl`,`t_laporan_thl`.`kode_thl`,`t_laporan_thl`.`kode_spv_kasie`,`t_laporan_thl`.`kode_spv_kabid`,`t_laporan_thl`.`skpd_id`,`t_laporan_thl`.`bidang_skpd_id`,`t_laporan_thl`.`jml_waktu`)select `t_thl`.`nama` AS `nama_thl`,`spv_kabid`.`nama` AS `nama_kabid`,`spv_kasie`.`nama` AS `nama_kasie`,`count_verifikasi`.`kode_thl` AS `kode_thl`,`count_verifikasi`.`kode_spv_kasie` AS `kode_spv_kasie`,`count_verifikasi`.`kode_spv_kabid` AS `kode_spv_kabid`,`m_skpd`.`id` AS `skpd_id`,`m_skpd`.`deskripsi` AS `skpd`,`m_bidang_skpd`.`id` AS `bidang_skpd_id`,`m_bidang_skpd`.`deskripsi` AS `bidang_skpd`,`count_verifikasi`.`tgl_laporan` AS `tgl_laporan`,`count_verifikasi`.`waktu` AS `waktu`,if(sum(`count_verifikasi`.`count_verifikasi`) > 0,'Dalam Proses Verifikasi','Selesai Proses Verifikasi') AS `status_verifikasi` from (((((`count_verifikasi` join `t_thl` on(`count_verifikasi`.`kode_thl` = `t_thl`.`kode_thl`)) join `m_skpd` on(`count_verifikasi`.`skpd_id` = `m_skpd`.`id`)) join `m_bidang_skpd` on(`count_verifikasi`.`bidang_skpd_id` = `m_bidang_skpd`.`id`)) left join `t_spv` `spv_kasie` on(`count_verifikasi`.`kode_spv_kasie` = `spv_kasie`.`kode_spv`)) left join `t_spv` `spv_kabid` on(`count_verifikasi`.`kode_spv_kabid` = `spv_kabid`.`kode_spv`)) group by `t_thl`.`nama`,`spv_kabid`.`nama`,`spv_kasie`.`nama`,`count_verifikasi`.`kode_thl`,`count_verifikasi`.`kode_spv_kasie`,`count_verifikasi`.`kode_spv_kabid`,`m_skpd`.`id`,`m_skpd`.`deskripsi`,`m_bidang_skpd`.`id`,`m_bidang_skpd`.`deskripsi`,`count_verifikasi`.`tgl_laporan`,`count_verifikasi`.`waktu` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_author`
--
ALTER TABLE `m_author`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_bidang_skpd`
--
ALTER TABLE `m_bidang_skpd`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `skpd_id` (`skpd_id`) USING BTREE;

--
-- Indexes for table `m_jabatan_spv`
--
ALTER TABLE `m_jabatan_spv`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `bidang_skpd_id` (`bidang_skpd_id`) USING BTREE;

--
-- Indexes for table `m_kegiatan_thl`
--
ALTER TABLE `m_kegiatan_thl`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `profesi_thl_id` (`profesi_thl_id`) USING BTREE;

--
-- Indexes for table `m_pendidikan`
--
ALTER TABLE `m_pendidikan`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_profesi_thl`
--
ALTER TABLE `m_profesi_thl`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_provinsi`
--
ALTER TABLE `m_provinsi`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_skpd`
--
ALTER TABLE `m_skpd`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_stat_akun`
--
ALTER TABLE `m_stat_akun`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_stat_laporan`
--
ALTER TABLE `m_stat_laporan`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_stat_perkawinan`
--
ALTER TABLE `m_stat_perkawinan`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `m_wilayah`
--
ALTER TABLE `m_wilayah`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `regencies_province_id_index` (`provinsi_id`) USING BTREE;

--
-- Indexes for table `trx_log_akun`
--
ALTER TABLE `trx_log_akun`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `kode` (`kode`) USING BTREE;

--
-- Indexes for table `trx_thl_pengalaman_kerja`
--
ALTER TABLE `trx_thl_pengalaman_kerja`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `trx_thl_sertfikat`
--
ALTER TABLE `trx_thl_sertfikat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trx_thl_sertifikat`
--
ALTER TABLE `trx_thl_sertifikat`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `t_akun`
--
ALTER TABLE `t_akun`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD UNIQUE KEY `kode` (`kode`) USING BTREE,
  ADD KEY `kode_2` (`kode`,`author_id`) USING BTREE,
  ADD KEY `author_id` (`author_id`) USING BTREE,
  ADD KEY `stat_akun_id` (`stat_akun_id`) USING BTREE;

--
-- Indexes for table `t_laporan_kegiatan_thl`
--
ALTER TABLE `t_laporan_kegiatan_thl`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `kode_laporan_thl` (`kode_laporan_thl`) USING BTREE,
  ADD KEY `kegiatan_thl_id` (`kegiatan_thl_id`) USING BTREE,
  ADD KEY `stat_laporan_id` (`stat_laporan_id`) USING BTREE;

--
-- Indexes for table `t_laporan_lain_thl`
--
ALTER TABLE `t_laporan_lain_thl`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `t_laporan_thl`
--
ALTER TABLE `t_laporan_thl`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `kode_laporan_thl` (`kode_laporan_thl`) USING BTREE,
  ADD KEY `skpd_id` (`skpd_id`) USING BTREE,
  ADD KEY `kode_spv` (`kode_spv_kasie`) USING BTREE,
  ADD KEY `kode_thl` (`kode_thl`) USING BTREE,
  ADD KEY `created_by` (`created_by`) USING BTREE,
  ADD KEY `profesi_thl_id` (`profesi_thl_id`) USING BTREE,
  ADD KEY `kode_laporan_thl_2` (`kode_laporan_thl`,`skpd_id`,`kode_thl`,`kode_spv_kasie`,`profesi_thl_id`) USING BTREE,
  ADD KEY `bidang_skpd_id` (`bidang_skpd_id`) USING BTREE;

--
-- Indexes for table `t_spv`
--
ALTER TABLE `t_spv`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `kode_spv` (`kode_spv`,`skpd_id`,`jabatan_spv_id`) USING BTREE,
  ADD KEY `t_spv_ibfk_1` (`skpd_id`) USING BTREE,
  ADD KEY `t_spv_ibfk_2` (`jabatan_spv_id`) USING BTREE,
  ADD KEY `created_by` (`created_by`) USING BTREE,
  ADD KEY `bidang_skpd_id` (`bidang_skpd_id`) USING BTREE;

--
-- Indexes for table `t_target_detail_thl`
--
ALTER TABLE `t_target_detail_thl`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `kode_target_thl` (`kode_target_thl`) USING BTREE;

--
-- Indexes for table `t_target_thl`
--
ALTER TABLE `t_target_thl`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `kode_target_thl` (`kode_target_thl`) USING BTREE,
  ADD KEY `skpd_id` (`skpd_id`) USING BTREE,
  ADD KEY `profesi_thl_id` (`profesi_thl_id`) USING BTREE,
  ADD KEY `kode_spv` (`kode_spv_kasie`) USING BTREE,
  ADD KEY `kode_thl` (`kode_thl`) USING BTREE,
  ADD KEY `bidang_skpd_id` (`bidang_skpd_id`) USING BTREE,
  ADD KEY `created_by` (`created_by`) USING BTREE,
  ADD KEY `stat_laporan_id` (`stat_laporan_id`) USING BTREE,
  ADD KEY `kode_spv_kabid` (`kode_spv_kabid`) USING BTREE,
  ADD KEY `kode_target_thl_2` (`kode_target_thl`,`skpd_id`,`bidang_skpd_id`,`profesi_thl_id`,`kode_thl`,`kode_spv_kasie`,`kode_spv_kabid`) USING BTREE;

--
-- Indexes for table `t_thl`
--
ALTER TABLE `t_thl`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `kode_thl` (`kode_thl`) USING BTREE,
  ADD KEY `skpd_id` (`skpd_id`) USING BTREE,
  ADD KEY `kode_thl_2` (`kode_thl`,`skpd_id`,`profesi_thl_id`) USING BTREE,
  ADD KEY `t_thl_ibfk_2` (`profesi_thl_id`) USING BTREE,
  ADD KEY `created_by` (`created_by`) USING BTREE,
  ADD KEY `kode_spv` (`kode_spv_kasie`) USING BTREE,
  ADD KEY `tmpt_lahir` (`tmpt_lahir`) USING BTREE,
  ADD KEY `stat_perkawinan_id` (`stat_perkawinan_id`) USING BTREE,
  ADD KEY `pendidikan_id` (`pendidikan_id`) USING BTREE,
  ADD KEY `bidang_skpd_id` (`bidang_skpd_id`) USING BTREE,
  ADD KEY `kota` (`tmpt_asal`) USING BTREE,
  ADD KEY `kode_spv_kabid` (`kode_spv_kabid`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_author`
--
ALTER TABLE `m_author`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_bidang_skpd`
--
ALTER TABLE `m_bidang_skpd`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `m_jabatan_spv`
--
ALTER TABLE `m_jabatan_spv`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `m_kegiatan_thl`
--
ALTER TABLE `m_kegiatan_thl`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `m_pendidikan`
--
ALTER TABLE `m_pendidikan`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `m_profesi_thl`
--
ALTER TABLE `m_profesi_thl`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_skpd`
--
ALTER TABLE `m_skpd`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `m_stat_akun`
--
ALTER TABLE `m_stat_akun`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_stat_laporan`
--
ALTER TABLE `m_stat_laporan`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_stat_perkawinan`
--
ALTER TABLE `m_stat_perkawinan`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trx_log_akun`
--
ALTER TABLE `trx_log_akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `trx_thl_pengalaman_kerja`
--
ALTER TABLE `trx_thl_pengalaman_kerja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trx_thl_sertfikat`
--
ALTER TABLE `trx_thl_sertfikat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `trx_thl_sertifikat`
--
ALTER TABLE `trx_thl_sertifikat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `t_akun`
--
ALTER TABLE `t_akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `t_laporan_kegiatan_thl`
--
ALTER TABLE `t_laporan_kegiatan_thl`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_laporan_lain_thl`
--
ALTER TABLE `t_laporan_lain_thl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `t_laporan_thl`
--
ALTER TABLE `t_laporan_thl`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_spv`
--
ALTER TABLE `t_spv`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `t_target_detail_thl`
--
ALTER TABLE `t_target_detail_thl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `t_target_thl`
--
ALTER TABLE `t_target_thl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `t_thl`
--
ALTER TABLE `t_thl`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_bidang_skpd`
--
ALTER TABLE `m_bidang_skpd`
  ADD CONSTRAINT `m_bidang_skpd_ibfk_1` FOREIGN KEY (`skpd_id`) REFERENCES `m_skpd` (`id`);

--
-- Constraints for table `m_kegiatan_thl`
--
ALTER TABLE `m_kegiatan_thl`
  ADD CONSTRAINT `m_kegiatan_thl_ibfk_1` FOREIGN KEY (`profesi_thl_id`) REFERENCES `m_profesi_thl` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
