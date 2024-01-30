#!/usr/bin/python3
# -*- coding: utf-8 -*-

#import paramiko
import sys
import socket
#from scp import SCPClient
from datetime import datetime, timedelta
import mysql.connector
import gzip
import shutil
import subprocess
import xlsxwriter
import datetime
import os
import re
#import MySQLdb 

filename = "controle_technique_v2.sql"

olddb = 'controle_technique'
oldhost = '192.168.88.254'
olduser = 'kaira'
oldpass = 'Maxi.Valencia_88'

newdb ='controle_technique_v2'
newhost = 'localhost'
newuser = 'root'
newpass = 'root'

max_line_number = 10000

def RecuperationTableCtZoneDesserte():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_zone_desserte`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_zone_deserte`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `zd_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtVisiteExtraTarif():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_visite_extra_tarif`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    # cursor = cnx.cursor()
    # query = "SELECT * FROM `ct_visite_extra_tarif`"
    # cursor.execute(query)
    # header = [row[0] for row in cursor.description]
    # rows = cursor.fetchall()
    # cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_imprime_tech_id_id`, `vet_annee`, `vet_prix`, `ct_arrete_prix_id_id`) VALUES")
        i = 0
        rows = [(1, 1, "2017", 2000, 1), (2, 2, "2017", 2000, 1), (3, 3, "2017", 2000, 1), (4, 4, "2017", 2000, 1), (5, 5, "2017", 2000, 1), (6, 6, "2017", 2000, 1), (7, 7, "2017", 2000, 1), (8, 8, "2017", 2000, 1), (9, 9, "2017", 2000, 1), (10, 10, "2017", 2000, 1), (11, 11, "2017", 2000, 1), (12, 12, "2017", 6000, 1), (13, 13, "2017", 6000, 1), (14, 14, "2017", 6000, 1), (15, 15, "2017", 6000, 1), (16, 1, "2023", 5000, 3), (17, 2, "2023", 5000, 3), (18, 3, "2023", 5000, 3), (19, 4, "2023", 5000, 3), (20, 5, "2023", 5000, 3), (21, 6, "2023", 5000, 3), (22, 7, "2023", 5000, 3), (23, 8, "2023", 5000, 3), (24, 9, "2023", 5000, 3), (25, 10, "2023", 5000, 3), (26, 11, "2023", 5000, 3), (27, 12, "2023", 12000, 3), (28, 13, "2023", 12000, 3), (29, 14, "2023", 12000, 3), (30, 15, "2023", 12000, 3)]
        for row in rows:
            #lst_row = list(row)
            #lst_row[2] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtVisiteExtra():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_visite_extra`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    # query = "SELECT COUNT(ALL `id`) FROM `ct_visite_extra`"
    # query = "SELECT `id`, `nom_imprime_tech` as `vste_libelle` FROM `ct_imprime_tech`"
    query = "SELECT * FROM `ct_imprime_tech`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            # query = "SELECT * FROM `ct_visite_extra` LIMIT " + str(min) + " ," + str(max)
            query = "SELECT `id`, `nom_imprime_tech` as `vste_libelle` FROM `ct_imprime_tech` ORDER BY `id` ASC LIMIT 11"
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `vste_libelle`) VALUES")
            i = 0
            for row in rows:
                #lst_row = list(row)
                #lst_row[0] = 1
                #row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number

        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtVisiteCtVisiteExtra():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_visite_ct_visite_extra`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `ct_visite_id`) FROM `ct_visite_visite_extra`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_visite_visite_extra` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`ct_visite_extra_id`, `ct_visite_id`) VALUES")
            i = 0
            for row in rows:
                #lst_row = list(row)
                #lst_row[0] = 1
                #row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
            
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtVisiteCtAnomalie():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_visite_ct_anomalie`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `ct_anomalie_id`) FROM `ct_visite_anomalie`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_visite_anomalie` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            
            f.write("INSERT IGNORE INTO " + table_name + " (`ct_anomalie_id`, `ct_visite_id`) VALUES")
            i = 0
            for row in rows:
                #lst_row = list(row)
                #lst_row[0] = 1
                #row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
            
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtVisite():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_visite`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_visite`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_visite` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_carte_grise_id_id`, `ct_centre_id_id`, `ct_type_visite_id_id`, `ct_usage_id_id`, `ct_user_id_id`, `ct_verificateur_id_id`, `vst_num_pv`, `vst_num_feuille_caisse`, `vst_date_expiration`, `vst_created`, `vst_updated`, `ct_utilisation_id_id`, `vst_is_apte`, `vst_is_contre_visite`, `vst_duree_reparation`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[7] is None :
                    lst_row[7] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[12] is None :
                    lst_row[12] = 'null'
                if lst_row[13] is None :
                    lst_row[13] = 'null'
                if lst_row[14] is None :
                    lst_row[14] = 'null'
                if lst_row[15] is None :
                    lst_row[15] = 'null'
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[9] is not None :
                    lst_row[9] = lst_row[9].strftime("%Y-%d-%m")
                else:
                    lst_row[9] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[10] is not None :
                    lst_row[10] = lst_row[10].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[10] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                if lst_row[11] is not None :
                    lst_row[11] = lst_row[11].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[11] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
        
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtVehicule():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_vehicule`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_vehicule`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_vehicule` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_genre_id_id`, `ct_marque_id_id`, `vhc_cylindre`, `vhc_puissance`, `vhc_poids_vide`, `vhc_charge_utile`, `vhc_hauteur`, `vhc_largeur`, `vhc_longueur`, `vhc_num_serie`, `vhc_num_moteur`, `vhc_created`, `vhc_provenance`, `vhc_type`, `vhc_poids_total_charge`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[7] is None :
                    lst_row[7] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[9] is None :
                    lst_row[9] = 'null'
                if lst_row[10] is None :
                    lst_row[10] = 'null'
                if lst_row[11] is None :
                    lst_row[11] = 'null'
                if lst_row[13] is None :
                    lst_row[13] = 'null'
                if lst_row[14] is None :
                    lst_row[14] = 'null'
                if lst_row[15] is None :
                    lst_row[15] = 'null'
                if lst_row[12] is not None :
                    lst_row[12] = lst_row[12].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[12] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
        
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtUtilisation():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_utilisation`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_utilisation`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ut_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtUser():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_user`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT `id`, `username`, `email`, `enabled`, `password`, `last_login`, `roles`, `usr_name`, `usr_adresse`, `usr_created_at`, `usr_updated_at`, `usr_telephone`, `ct_centre_id`, `ct_role_id` FROM `ct_user`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `username`, `usr_mail`, `usr_enable`, `password`, `usr_last_login`, `roles`, `usr_nom`, `usr_adresse`, `usr_created_at`, `usr_updated_at`, `usr_telephone`, `ct_centre_id_id`, `ct_role_id_id`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[8] is None :
                lst_row[8] = 'null'
            if lst_row[11] is None :
                lst_row[11] = 'null'
            if lst_row[5] is not None :
                lst_row[5] = lst_row[5].strftime("%Y-%d-%m %H:%M:%S")
            else:
                lst_row[5] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
            if lst_row[9] is not None :
                lst_row[9] = lst_row[9].strftime("%Y-%d-%m %H:%M:%S")
            else:
                lst_row[9] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
            if lst_row[10] is not None :
                lst_row[10] = lst_row[10].strftime("%Y-%d-%m %H:%M:%S")
            else:
                lst_row[10] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
            if("ROLE_SUPERADMIN" in lst_row[6]):
                lst_row[6] = '["ROLE_ADMIN"]'
                lst_row[13] = 2
            if("ROLE_ADMIN" in lst_row[6]):
                lst_row[6] = '["ROLE_CHEF_DE_CENTRE"]'
                lst_row[13] = 22
            if("ROLE_VERIFICATEUR" in lst_row[6]):
                lst_row[6] = '["ROLE_VERIFICATEUR"]'
                lst_row[13] = 3
            if("ROLE_APPROVISIONNEMENT" in lst_row[6]):
                lst_row[6] = '["ROLE_APPROVISIONNEMENT"]'
                lst_row[13] = 7
            if("ROLE_STAFF" in lst_row[6]):
                lst_row[6] = '["ROLE_STAFF"]'
                lst_row[13] = 6
            if("ROLE_RECEPTION" in lst_row[6]):
                lst_row[6] = '["ROLE_CONSTATATION_RECEPTION"]'
                lst_row[13] = 11
            if("ROLE_VISITE" in lst_row[6]):
                lst_row[6] = '["ROLE_VISITE"]'
                lst_row[13] = 5
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtUsageTarif():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_usage_tarif`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_usage_tarif`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_usage_id_id`, `usg_trf_annee`, `usg_trf_prix`, `ct_type_visite_id_id`, `ct_arrete_prix_id_id`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtUsageImprimeTechnique():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '``'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtUsage():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_usage`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_usage`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        # f.write("INSERT IGNORE INTO " + table_name + " (`id`, `usg_libelle`, `usg_validite`, `usg_created`, `ct_type_usage_id_id`) VALUES")
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `usg_libelle`, `usg_validite`, `usg_created`, `ct_type_usage_id_id`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[3] is not None :
                lst_row[3] = lst_row[3].strftime("%Y-%d-%m")
            else:
                lst_row[3] = datetime.datetime.now().strftime("%Y-%d-%m")
            lst_row[4] = 1
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtTypeVisite():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_type_visite`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_type_visite`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `tpv_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtTypeUsage():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_type_usage`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_type_usage`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `tpu_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtTypeReception():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_type_reception`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_type_reception`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `tprcp_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtTypeDroitPTAC():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_type_droit_ptac`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_type_droit_ptac`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `tp_dp_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtSourceEnergie():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_source_energie`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_source_energie`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `sre_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtAutre():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_autre`'
    #cnx = mysql.connector.connect(
    #    host=oldhost,
    #    database=olddb,
    #    user=olduser,
    #    password=oldpass
    #)
    #cursor = cnx.cursor()
    #query = "SELECT * FROM `ct_role`"
    #cursor.execute(query)
    #header = [row[0] for row in cursor.description]
    #rows = cursor.fetchall()
    #cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `nom`, `attribut`) VALUES")
        i = 0
        rows = [(1, "DEPLOIEMENT", "16/01/2024"), (2, "TVA", "20"), (3, "TIMBRE", "0")]
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtAutreDonne():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_autre_donne`'
    #cnx = mysql.connector.connect(
    #    host=oldhost,
    #    database=olddb,
    #    user=olduser,
    #    password=oldpass
    #)
    #cursor = cnx.cursor()
    #query = "SELECT * FROM `ct_role`"
    #cursor.execute(query)
    #header = [row[0] for row in cursor.description]
    #rows = cursor.fetchall()
    #cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `nom`, `attribut`) VALUES")
        i = 0
        rows = [(1, "DEPLOIEMENT", "16/01/2024"), (2, "TVA", "20"), (3, "TIMBRE", "0")]
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtRole():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_role`'
    #cnx = mysql.connector.connect(
    #    host=oldhost,
    #    database=olddb,
    #    user=olduser,
    #    password=oldpass
    #)
    #cursor = cnx.cursor()
    #query = "SELECT * FROM `ct_role`"
    #cursor.execute(query)
    #header = [row[0] for row in cursor.description]
    #rows = cursor.fetchall()
    #cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `role_name`) VALUES")
        i = 0
        rows = [(1, "SUPER_ADMIN"), (2, "ADMIN"), (3, "VERIFICATEUR"), (4, "RECEPTION"), (5, "VISITE"), (6, "STAFF"), (7, "APPROVISIONNEMENT"), (8, "REGISSEUR"), (9, "CONTROLE_TECHNIQUE"), (10, "CONSTATATION"), (11, "CONSTATATION_RECEPTION"), (12, "CONSTATATION_VISITE"), (13, "RECEPTION_VISITE"), (14, "CONSTATATION_RECEPTION_VISITE"), (15, "REGISSEUR_CONSTATATION"), (16, "REGISSEUR_RECEPTION"), (17, "REGISSEUR_VISITE"), (18, "REGISSEUR_CONSTATATION_RECEPTION"), (19, "REGISSEUR_CONSTATATION_VISITE"), (20, "REGISSEUR_RECEPTION_VISITE"), (21, "REGISSEUR_CONSTATATION_RECEPTION_VISITE"), (22, "CHEF_DE_CENTRE"), (23, "USER")]
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtReception():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_reception`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_reception`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
        
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_reception` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()

            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_centre_id_id`, `ct_motif_id_id`, `ct_type_reception_id_id`, `ct_user_id_id`, `ct_utilisation_id_id`, `ct_vehicule_id_id`, `rcp_mise_service`, `rcp_immatriculation`, `rcp_proprietaire`, `rcp_profession`, `rcp_adresse`, `rcp_nbr_assis`, `rcp_ngr_debout`, `rcp_num_pv`, `ct_source_energie_id_id`, `ct_carrosserie_id_id`, `rcp_num_group`, `rcp_created`, `ct_genre_id_id`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[9] is None :
                    lst_row[9] = 'null'
                if lst_row[10] is None :
                    lst_row[10] = 'null'
                if lst_row[11] is None :
                    lst_row[11] = 'null'
                if lst_row[12] is None :
                    lst_row[12] = 'null'
                if lst_row[13] is None :
                    lst_row[13] = 'null'
                if lst_row[14] is None :
                    lst_row[14] = 'null'
                if lst_row[15] is None :
                    lst_row[15] = 'null'
                if lst_row[16] is None :
                    lst_row[16] = 'null'
                if lst_row[17] is None :
                    lst_row[17] = 'null'
                if lst_row[19] is None :
                    lst_row[19] = 'null'
                if lst_row[7] is not None :
                    lst_row[7] = lst_row[7].strftime("%Y-%d-%m")
                else:
                    lst_row[7] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[18] is not None :
                    lst_row[18] = lst_row[18].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[18] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
        
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtProvince():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_province`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_province`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `prv_nom`, `prv_code`, `prv_created_at`, `prv_updated_at`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[3] is not None :
                lst_row[3] = lst_row[3].strftime("%Y-%d-%m")
            else:
                lst_row[3] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[4] is not None :
                lst_row[4] = lst_row[4].strftime("%Y-%d-%m")
            else:
                lst_row[4] = datetime.datetime.now().strftime("%Y-%d-%m")
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtProcesVerbal():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_proces_verbal`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_proces_verbal`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `pv_type`, `pv_tarif`, `ct_arrete_prix_id_id`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtPhoto():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '``'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtMotifTarif():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_motif_tarif`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_motif_tarif`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_motif_id_id`, `mtf_trf_prix`, `mtf_trf_date`, `ct_arrete_prix_id`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[3] is not None :
                lst_row[3] = datetime.date(int(lst_row[3]), 1, 1).strftime("%Y-%d-%m")
                #lst_row[3] = lst_row[3].strftime("%Y-01-01")
                # lst_row[3] = lst_row[3].strftime("%Y-%m-%d")
            else:
                lst_row[3] = datetime.datetime.now().strftime("%Y-%d-%m")
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtMotif():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_motif`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_motif`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `mtf_libelle`, `mtf_is_calculable`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtMarque():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_marque`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_marque`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `mrq_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtImprimeTechUse():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_imprime_tech_use`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_imprime_tech_use`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_imprime_tech_use` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_bordereau_id_id`, `ct_centre_id_id`, `ct_imprime_tech_id_id`, `ct_user_id_id`, `ct_controle_id`, `itu_numero`, `itu_used`, `actived_at`, `created_at`, `updated_at`, `itu_observation`, `itu_is_visible`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[7] is None :
                    lst_row[7] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[9] is not None :
                    lst_row[9] = lst_row[9].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[9] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                if lst_row[10] is not None :
                    lst_row[10] = lst_row[10].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[10] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                if lst_row[11] is not None :
                    lst_row[11] = lst_row[11].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[11] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                if lst_row[11] is None :
                    lst_row[11] = 'null'
                if lst_row[12] is None :
                    lst_row[12] = 1
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
        
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtImprimeTech():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_imprime_tech`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_imprime_tech`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_user_id_id`, `nom_imprime_tech`, `ute_imprime_tech`, `abrev_imprime_tech`, `prtt_created_at`, `prtt_updated_at`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[5] is not None :
                lst_row[5] = lst_row[5].strftime("%Y-%d-%m")
            else:
                lst_row[5] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[6] is not None :
                lst_row[6] = lst_row[6].strftime("%Y-%d-%m")
            else:
                lst_row[6] = datetime.datetime.now().strftime("%Y-%d-%m")
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtHistoriqueType():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_historique_type`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `hst_libelle`) VALUES")
        i = 0
        rows = [(1, "VISITE"), (2, "RECEPTION"), (3, "CONSTATATION"), (4, "CARTE-GRISE")]
        for row in rows:
            # lst_row = list(row)
            # lst_row[0] = 1
            # row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtHistorique():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_historique`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_historique`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,\n')
        # f.write('  `zd_libelle` varchar(255) NOT NULL,\n')
        # f.write('  PRIMARY KEY (`id`)\n')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_historique` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `hst_description`, `hst_date_created_at`, `ct_user_id_id`, `hst_is_view`, `ct_center_id_id`, `ct_historique_type_id_id`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                else:
                    if lst_row[6] == "VISITE" :
                        lst_row[6] = 1
                    if lst_row[6] == "RECEPTION" :
                        lst_row[6] = 2
                    if lst_row[6] == "CONSTATATION AVANT D" :
                        lst_row[6] = 3
                    if lst_row[6] == "CARTE-GRISE" :
                        lst_row[6] = 4
                if lst_row[2] is not None :
                    lst_row[2] = lst_row[2].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[2] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
    
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtGenreTarif():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_genre_tarif`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, ``) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtGenreCategorie():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_genre_categorie`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_genre_categorie`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `id` int(11) NOT NULL AUTO_INCREMENT,
          # `gc_libelle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `gc_is_calculable` tinyint(1) NOT NULL,
          # PRIMARY KEY (`id`)
        # ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `gc_libelle`, `gc_is_calculable`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtGenre():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_genre`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_genre`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `id` int(11) NOT NULL AUTO_INCREMENT,
          # `gr_libelle` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `gr_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `ct_genre_categorie_id` int(11) DEFAULT NULL,
          # PRIMARY KEY (`id`),
          # KEY `IDX_9BCBF2CE12DA9529` (`ct_genre_categorie_id`),
          # CONSTRAINT `FK_9BCBF2CE12DA9529` FOREIGN KEY (`ct_genre_categorie_id`) REFERENCES `ct_genre_categorie` (`id`)
        # ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `gr_libelle`, `gr_code`, `ct_genre_categorie_id_id`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtExtraVente():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '``'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, ``) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtDroitPTAC():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_droit_ptac`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_droit_ptac`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `id` int(11) NOT NULL AUTO_INCREMENT,
          # `ct_genre_categorie_id` int(11) DEFAULT NULL,
          # `dp_prix_min` double DEFAULT NULL,
          # `dp_prix_max` double DEFAULT NULL,
          # `dp_droit` double DEFAULT NULL,
          # `ct_type_droit_ptac_id` int(11) DEFAULT NULL,
          # `ct_arrete_prix_id` int(11) DEFAULT NULL,
          # PRIMARY KEY (`id`),
          # KEY `IDX_DB918ADA12DA9529` (`ct_genre_categorie_id`),
          # KEY `IDX_DB918ADA7CFDF4AC` (`ct_type_droit_ptac_id`),
          # KEY `IDX_DB918ADA76255A68` (`ct_arrete_prix_id`),
          # CONSTRAINT `FK_DB918ADA12DA9529` FOREIGN KEY (`ct_genre_categorie_id`) REFERENCES `ct_genre_categorie` (`id`),
          # CONSTRAINT `FK_DB918ADA76255A68` FOREIGN KEY (`ct_arrete_prix_id`) REFERENCES `ct_arrete_prix` (`id`) ON DELETE CASCADE,
          # CONSTRAINT `FK_DB918ADA7CFDF4AC` FOREIGN KEY (`ct_type_droit_ptac_id`) REFERENCES `ct_type_droit_ptac` (`id`)
        # ) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_genre_categorie_id_id`, `dp_prix_min`, `dp_prix_max`, `dp_droit`, `ct_type_droit_ptac_id_id`, `ct_arrete_prix_id_id`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if(lst_row[2] is None):
                lst_row[2] = 'null'
            if(lst_row[3] is None):
                lst_row[3] = 'null'
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtConstAvDedType():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_const_av_ded_type`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_const_av_ded_type`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `id` int(11) NOT NULL AUTO_INCREMENT,
          # `cad_tp_libelle` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          # PRIMARY KEY (`id`)
        # ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `cad_tp_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtConstAvDedCtConstAvDedCarac():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_const_av_ded_ct_const_av_ded_carac`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `const_av_ded_id`) FROM `ct_const_av_deds_const_av_ded_caracs`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `const_av_ded_id` int(11) NOT NULL,
          # `const_av_ded_carac_id` int(11) NOT NULL,
          # PRIMARY KEY (`const_av_ded_id`,`const_av_ded_carac_id`),
          # UNIQUE KEY `UNIQ_58B3C67A1E94B9F2` (`const_av_ded_carac_id`),
          # KEY `IDX_58B3C67AE4B53355` (`const_av_ded_id`),
          # CONSTRAINT `FK_58B3C67A1E94B9F2` FOREIGN KEY (`const_av_ded_carac_id`) REFERENCES `ct_const_av_ded_carac` (`id`),
          # CONSTRAINT `FK_58B3C67AE4B53355` FOREIGN KEY (`const_av_ded_id`) REFERENCES `ct_const_av_ded` (`id`)
        # ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_const_av_deds_const_av_ded_caracs` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`ct_const_av_ded_id`, `ct_const_av_ded_carac_id`) VALUES")
            i = 0
            for row in rows:
                #lst_row = list(row)
                #lst_row[0] = 1
                #row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            #min = max + 1
            #max = max + max_line_number
    
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtConstAvDedCarac():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_const_av_ded_carac`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_const_av_ded_carac`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `id` int(11) NOT NULL AUTO_INCREMENT,
          # `ct_carosserie_id` int(11) DEFAULT NULL,
          # `ct_const_av_ded_type_id` int(11) DEFAULT NULL,
          # `ct_genre_id` int(11) DEFAULT NULL,
          # `ct_marque_id` int(11) DEFAULT NULL,
          # `ct_source_energie_id` int(11) DEFAULT NULL,
          # `cad_cylindre` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_puissance` double DEFAULT NULL,
          # `cad_poids_vide` double DEFAULT NULL,
          # `cad_charge_utile` double DEFAULT NULL,
          # `cad_hauteur` double DEFAULT NULL,
          # `cad_largeur` double DEFAULT NULL,
          # `cad_longueur` double DEFAULT NULL,
          # `cad_num_serie_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_num_moteur` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_type_car` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_poids_maxima` text COLLATE utf8_unicode_ci,
          # `cad_poids_total_charge` double DEFAULT NULL,
          # `cad_premiere_circule` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_nbr_assis` int(11) DEFAULT NULL,
          # PRIMARY KEY (`id`),
          # KEY `fk_ct_const_av_ded_carac_ct_genre1_idx` (`ct_genre_id`),
          # KEY `fk_ct_const_av_ded_carac_ct_marque1_idx` (`ct_marque_id`),
          # KEY `fk_ct_const_av_ded_carac_ct_source_energie1_idx` (`ct_source_energie_id`),
          # KEY `fk_ct_const_av_ded_carac_ct_carosserie1_idx` (`ct_carosserie_id`),
          # KEY `fk_ct_const_av_ded_carac_ct_const_av_ded_type1_idx` (`ct_const_av_ded_type_id`),
          # CONSTRAINT `FK_FAC238B67EE62163` FOREIGN KEY (`ct_source_energie_id`) REFERENCES `ct_source_energie` (`id`),
          # CONSTRAINT `FK_FAC238B68CD3293F` FOREIGN KEY (`ct_marque_id`) REFERENCES `ct_marque` (`id`),
          # CONSTRAINT `FK_FAC238B6B08BD647` FOREIGN KEY (`ct_const_av_ded_type_id`) REFERENCES `ct_const_av_ded_type` (`id`),
          # CONSTRAINT `FK_FAC238B6D74CE6E6` FOREIGN KEY (`ct_genre_id`) REFERENCES `ct_genre` (`id`),
          # CONSTRAINT `FK_FAC238B6F2AE3878` FOREIGN KEY (`ct_carosserie_id`) REFERENCES `ct_carosserie` (`id`)
        # ) ENGINE=InnoDB AUTO_INCREMENT=33485 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_const_av_ded_carac` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_carrosserie_id_id`, `ct_const_av_ded_type_id_id`, `ct_genre_id_id`, `ct_marque_id_id`, `ct_source_energie_id_id`, `cad_cylindre`, `cad_puissance`, `cad_poids_vide`, `cad_charge_utile`, `cad_hauteur`, `cad_largeur`, `cad_longueur`, `cad_num_serie_type`, `cad_num_moteur`, `cad_type_car`, `cad_poids_maxima`, `cad_poids_total_charge`, `cad_premiere_circule`, `cad_nbr_assis`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[7] is None :
                    lst_row[7] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[9] is None :
                    lst_row[9] = 'null'
                if lst_row[10] is None :
                    lst_row[10] = 'null'
                if lst_row[11] is None :
                    lst_row[11] = 'null'
                if lst_row[12] is None :
                    lst_row[12] = 'null'
                if lst_row[13] is None :
                    lst_row[13] = 'null'
                if lst_row[14] is None :
                    lst_row[14] = 'null'
                if lst_row[15] is None :
                    lst_row[15] = 'null'
                if lst_row[16] is None :
                    lst_row[16] = 'null'
                if lst_row[17] is None :
                    lst_row[17] = 'null'
                if lst_row[18] is None :
                    lst_row[18] = 'null'
                if lst_row[19] is None :
                    lst_row[19] = 'null'
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
        
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtConstAvDed():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_const_av_ded`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_const_av_ded`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
          # `id` int(11) NOT NULL AUTO_INCREMENT,
          # `ct_centre_id` int(11) DEFAULT NULL,
          # `ct_verificateur_id` int(11) DEFAULT NULL,
          # `cad_provenance` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_divers` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_proprietaire_nom` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_proprietaire_adresse` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_bon_etat` tinyint(1) DEFAULT NULL,
          # `cad_sec_pers` tinyint(1) DEFAULT NULL,
          # `cad_sec_march` tinyint(1) DEFAULT NULL,
          # `cad_protec_env` tinyint(1) DEFAULT NULL,
          # `cad_numero` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_immatriculation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_date_embarquement` datetime DEFAULT NULL,
          # `cad_lieu_embarquement` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
          # `cad_created` datetime DEFAULT NULL,
          # `cad_conforme` tinyint(1) DEFAULT NULL,
          # `cad_observation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
          # PRIMARY KEY (`id`),
          # KEY `fk_ct_const_av_ded_ct_user1_idx` (`ct_verificateur_id`),
          # KEY `fk_ct_const_av_ded_ct_centre1_idx` (`ct_centre_id`),
          # CONSTRAINT `FK_5116CBD82C8474E` FOREIGN KEY (`ct_centre_id`) REFERENCES `ct_centre` (`id`),
          # CONSTRAINT `FK_5116CBDBDF4F30F` FOREIGN KEY (`ct_verificateur_id`) REFERENCES `ct_user` (`id`)
        # ) ENGINE=InnoDB AUTO_INCREMENT=11160 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_const_av_ded` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_centre_id_id`, `ct_verificateur_id_id`, `cad_provenance`, `cad_divers`, `cad_proprietaire_nom`, `cad_proprietaire_adresse`, `cad_bon_etat`, `cad_sec_pers`, `cad_sec_march`, `cad_protec_env`, `cad_numero`, `cad_immatriculation`, `cad_date_embarquement`, `cad_lieu_embarquement`, `cad_created`, `cad_conforme`, `cad_observation`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[5] is None :
                    lst_row[5] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[7] is None :
                    lst_row[7] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[9] is None :
                    lst_row[9] = 'null'
                if lst_row[10] is None :
                    lst_row[10] = 'null'
                if lst_row[11] is None :
                    lst_row[11] = 'null'
                if lst_row[12] is None :
                    lst_row[12] = 'null'
                if lst_row[14] is None :
                    lst_row[14] = 'null'
                if lst_row[16] is None :
                    lst_row[16] = 'null'
                if lst_row[17] is None :
                    lst_row[17] = 'null'
                if lst_row[13] is not None :
                    lst_row[13] = lst_row[13].strftime("%Y-%d-%m")
                else:
                    lst_row[13] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[15] is not None :
                    lst_row[15] = lst_row[15].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[15] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
        
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtCentre():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_centre`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_centre`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_province_id` int(11) DEFAULT NULL,')
        # f.write('  `ctr_nom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `ctr_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `ctr_created_at` datetime DEFAULT NULL,')
        # f.write('  `ctr_updated_at` datetime DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  KEY `fk_ct_centre_ct_province1_idx` (`ct_province_id`),')
        # f.write('  CONSTRAINT `FK_902E42D9764A0FC` FOREIGN KEY (`ct_province_id`) REFERENCES `ct_province` (`id`) ON DELETE CASCADE')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_province_id_id`, `ctr_nom`, `ctr_code`, `ctr_created_at`, `ctr_updated_at`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[1] is None :
                lst_row[1] = 6
            if lst_row[4] is not None :
                lst_row[4] = lst_row[4].strftime("%Y-%d-%m")
            else:
                lst_row[4] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[5] is not None :
                lst_row[5] = lst_row[5].strftime("%Y-%d-%m")
            else:
                lst_row[5] = datetime.datetime.now().strftime("%Y-%d-%m")
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtCarteGrise():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_carte_grise`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    
    query = "SELECT  COUNT(ALL `id`) FROM `ct_carte_grise`"
    cursor.execute(query)
    result = cursor.fetchone()
    line_count  = result[0]
    quotient = line_count // max_line_number
    reste = line_count % max_line_number
    min = 1
    max = max_line_number
    if reste > 0:
        quotient = quotient + 1
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_carosserie_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_centre_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_source_energie_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_vehicule_id` int(11) DEFAULT NULL,')
        # f.write('  `cg_date_emission` date DEFAULT NULL,')
        # f.write('  `cg_nom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_prenom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_profession` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_adresse` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_phone` varchar(255) CHARACTER SET utf8 DEFAULT NULL,')
        # f.write('  `cg_commune` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_nbr_assis` int(11) DEFAULT NULL,')
        # f.write('  `cg_nbr_debout` int(11) DEFAULT NULL,')
        # f.write('  `cg_puissance_admin` int(11) DEFAULT NULL,')
        # f.write('  `cg_mise_en_service` date DEFAULT NULL,')
        # f.write('  `cg_patente` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_ani` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_rta` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_num_carte_violette` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_date_carte_violette` date DEFAULT NULL,')
        # f.write('  `cg_lieu_carte_violette` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_num_vignette` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_date_vignette` date DEFAULT NULL,')
        # f.write('  `cg_lieu_vignette` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_immatriculation` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_created` datetime DEFAULT NULL,')
        # f.write('  `cg_nom_cooperative` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_itineraire` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_is_transport` tinyint(1) NOT NULL,')
        # f.write('  `cg_num_identification` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `cg_zone_deserte` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,')
        # f.write('  `ct_zone_deserte_id` int(11) DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  UNIQUE KEY `UNIQ_A447BE7316321FAD` (`cg_immatriculation`),')
        # f.write('  KEY `fk_ct_carte_grise_ct_carosserie1_idx` (`ct_carosserie_id`),')
        # f.write('  KEY `fk_ct_carte_grise_ct_source_energie1_idx` (`ct_source_energie_id`),')
        # f.write('  KEY `fk_ct_carte_grise_ct_vehicule1_idx` (`ct_vehicule_id`),')
        # f.write('  KEY `fk_ct_carte_grise_ct_centre1_idx` (`ct_centre_id`),')
        # f.write('  KEY `FK_A447BE73C50880EA` (`ct_zone_deserte_id`),')
        # f.write('  CONSTRAINT `FK_A447BE73346884A7` FOREIGN KEY (`ct_vehicule_id`) REFERENCES `ct_vehicule` (`id`),')
        # f.write('  CONSTRAINT `FK_A447BE737EE62163` FOREIGN KEY (`ct_source_energie_id`) REFERENCES `ct_source_energie` (`id`),')
        # f.write('  CONSTRAINT `FK_A447BE7382C8474E` FOREIGN KEY (`ct_centre_id`) REFERENCES `ct_centre` (`id`),')
        # f.write('  CONSTRAINT `FK_A447BE73C50880EA` FOREIGN KEY (`ct_zone_deserte_id`) REFERENCES `ct_zone_deserte` (`id`),')
        # f.write('  CONSTRAINT `FK_A447BE73F2AE3878` FOREIGN KEY (`ct_carosserie_id`) REFERENCES `ct_carosserie` (`id`)')
        # f.write(') ENGINE=InnoDB AUTO_INCREMENT=303368 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        
        for j in range(quotient):
            cnx = mysql.connector.connect(
                host=oldhost,
                database=olddb,
                user=olduser,
                password=oldpass
            )
            cursor = cnx.cursor()
            if j == 0:
                min = 0
                max = max_line_number - 1
            else:
                min = (max * j) - 1
                max = max_line_number
            query = "SELECT * FROM `ct_carte_grise` LIMIT " + str(min) + " ," + str(max)
            cursor.execute(query)
            header = [row[0] for row in cursor.description]
            rows = cursor.fetchall()
            cnx.close()
            f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_carrosserie_id_id`, `ct_centre_id_id`, `ct_source_energie_id_id`, `ct_vehicule_id_id`, `cg_date_emission`, `cg_nom`, `cg_prenom`, `cg_profession`, `cg_adresse`, `cg_phone`, `cg_commune`, `cg_nbr_assis`, `cg_nbr_debout`, `cg_puissance_admin`, `cg_mise_en_service`, `cg_patente`, `cg_ani`, `cg_rta`, `cg_num_carte_violette`, `cg_date_carte_violette`, `cg_lieu_carte_violette`, `cg_num_vignette`, `cg_date_vignette`, `cg_lieu_vignette`, `cg_immatriculation`, `cg_created`, `cg_nom_cooperative`, `cg_itineraire`, `cg_is_transport`, `cg_num_identification`, `cg_observation`, `ct_zone_desserte_id_id`) VALUES")
            i = 0
            for row in rows:
                lst_row = list(row)
                if lst_row[1] is None :
                    lst_row[1] = 'null'
                if lst_row[2] is None :
                    lst_row[2] = 'null'
                if lst_row[3] is None :
                    lst_row[3] = 'null'
                if lst_row[4] is None :
                    lst_row[4] = 'null'
                if lst_row[6] is None :
                    lst_row[6] = 'null'
                if lst_row[7] is None :
                    lst_row[7] = 'null'
                if lst_row[8] is None :
                    lst_row[8] = 'null'
                if lst_row[9] is None :
                    lst_row[9] = 'null'
                if lst_row[10] is None :
                    lst_row[10] = 'null'
                if lst_row[11] is None :
                    lst_row[11] = 'null'
                if lst_row[12] is None :
                    lst_row[12] = 'null'
                if lst_row[13] is None :
                    lst_row[13] = 'null'
                if lst_row[14] is None :
                    lst_row[14] = 'null'
                if lst_row[16] is None :
                    lst_row[16] = 'null'
                if lst_row[17] is None :
                    lst_row[17] = 'null'
                if lst_row[18] is None :
                    lst_row[18] = 'null'
                if lst_row[19] is None :
                    lst_row[19] = 'null'
                if lst_row[21] is None :
                    lst_row[21] = 'null'
                if lst_row[22] is None :
                    lst_row[22] = 'null'
                if lst_row[24] is None :
                    lst_row[24] = 'null'
                if lst_row[25] is None :
                    lst_row[25] = 'null'
                if lst_row[27] is None :
                    lst_row[27] = 'null'
                if lst_row[28] is None :
                    lst_row[28] = 'null'
                if lst_row[29] is None :
                    lst_row[29] = 'null'
                if lst_row[30] is None :
                    lst_row[30] = 'null'
                if lst_row[31] is None :
                    lst_row[31] = 'null'
                if lst_row[32] is None :
                    lst_row[32] = 'null'
                if lst_row[5] is not None :
                    lst_row[5] = lst_row[5].strftime("%Y-%d-%m")
                else:
                    lst_row[5] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[15] is not None :
                    lst_row[15] = lst_row[15].strftime("%Y-%d-%m")
                else:
                    lst_row[15] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[20] is not None :
                    lst_row[20] = lst_row[20].strftime("%Y-%d-%m")
                else:
                    lst_row[20] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[23] is not None :
                    lst_row[23] = lst_row[23].strftime("%Y-%d-%m")
                else:
                    lst_row[23] = datetime.datetime.now().strftime("%Y-%d-%m")
                if lst_row[26] is not None :
                    lst_row[26] = lst_row[26].strftime("%Y-%d-%m %H:%M:%S")
                else:
                    lst_row[26] = datetime.datetime.now().strftime("%Y-%d-%m %H:%M:%S")
                row = tuple(lst_row)
                if(i > 0):
                    f.write(",") 
                f.write("%s" % str(row))
                i = i + 1
            f.write(";\n")
            # min = max + 1
            # max = max + max_line_number
    
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtCarrosserie():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_carrosserie`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_carosserie`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `crs_libelle` varchar(255) NOT NULL,')
        # f.write('  PRIMARY KEY (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `crs_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtBordereau():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_bordereau`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_bordereau`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_centre_id_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_imprime_tech_id_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_user_id_id` int(11) DEFAULT NULL,')
        # f.write('  `bl_numero` varchar(255) NOT NULL,')
        # f.write('  `bl_debut_numero` int(11) NOT NULL,')
        # f.write('  `bl_fin_numero` int(11) NOT NULL,')
        # f.write('  `bl_created_at` date NOT NULL,')
        # f.write('  `bl_updated_at` date DEFAULT NULL,')
        # f.write('  `ref_expr` varchar(255) DEFAULT NULL,')
        # f.write('  `date_ref_expr` date DEFAULT NULL,')
        # f.write('  `bl_observation` varchar(255) DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  KEY `IDX_334055EC36C2F638` (`ct_centre_id_id`),')
        # f.write('  KEY `IDX_334055ECB1D04D41` (`ct_imprime_tech_id_id`),')
        # f.write('  KEY `IDX_334055ECE4B6E02` (`ct_user_id_id`),')
        # f.write('  CONSTRAINT `FK_334055EC36C2F638` FOREIGN KEY (`ct_centre_id_id`) REFERENCES `ct_centre` (`id`),')
        # f.write('  CONSTRAINT `FK_334055ECB1D04D41` FOREIGN KEY (`ct_imprime_tech_id_id`) REFERENCES `ct_imprime_tech` (`id`),')
        # f.write('  CONSTRAINT `FK_334055ECE4B6E02` FOREIGN KEY (`ct_user_id_id`) REFERENCES `ct_user` (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO " + table_name + " (`id`, `ct_centre_id_id`, `ct_imprime_tech_id_id`, `ct_user_id_id`, `bl_numero`, `bl_debut_numero`, `bl_fin_numero`, `bl_created_at`, `bl_updated_at`, `ref_expr`, `date_ref_expr`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[7] is not None :
                lst_row[7] = lst_row[7].strftime("%Y-%d-%m")
            else:
                lst_row[7] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[8] is not None :
                lst_row[8] = lst_row[8].strftime("%Y-%d-%m")
            else:
                lst_row[8] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[10] is not None :
                lst_row[10] = lst_row[10].strftime("%Y-%d-%m")
            else:
                lst_row[10] = datetime.datetime.now().strftime("%Y-%d-%m")
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtAutreVente():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_autre_vente`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_usage_it_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_autre_tarif_id_id` int(11) DEFAULT NULL,')
        # f.write('  `user_id_id` int(11) DEFAULT NULL,')
        # f.write('  `verificateur_id_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_carte_grise_id_id` int(11) DEFAULT NULL,')
        # f.write('  `ct_centre_id_id` int(11) DEFAULT NULL,')
        # f.write('  `auv_is_visible` tinyint(1) NOT NULL,')
        # f.write('  `auv_created_at` datetime DEFAULT NULL,')
        # f.write('  `controle_id` int(11) DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  KEY `IDX_BD5B077BDBB11B80` (`ct_usage_it_id`),')
        # f.write('  KEY `IDX_BD5B077B3A162080` (`ct_autre_tarif_id_id`),')
        # f.write('  KEY `IDX_BD5B077B9D86650F` (`user_id_id`),')
        # f.write('  KEY `IDX_BD5B077B4A8E174A` (`verificateur_id_id`),')
        # f.write('  KEY `IDX_BD5B077BF8F2EE9` (`ct_carte_grise_id_id`),')
        # f.write('  KEY `IDX_BD5B077B36C2F638` (`ct_centre_id_id`),')
        # f.write('  CONSTRAINT `FK_BD5B077B36C2F638` FOREIGN KEY (`ct_centre_id_id`) REFERENCES `ct_centre` (`id`),')
        # f.write('  CONSTRAINT `FK_BD5B077B3A162080` FOREIGN KEY (`ct_autre_tarif_id_id`) REFERENCES `ct_autre_tarif` (`id`),')
        # f.write('  CONSTRAINT `FK_BD5B077B4A8E174A` FOREIGN KEY (`verificateur_id_id`) REFERENCES `ct_user` (`id`),')
        # f.write('  CONSTRAINT `FK_BD5B077B9D86650F` FOREIGN KEY (`user_id_id`) REFERENCES `ct_user` (`id`),')
        # f.write('  CONSTRAINT `FK_BD5B077BDBB11B80` FOREIGN KEY (`ct_usage_it_id`) REFERENCES `ct_usage_imprime_technique` (`id`),')
        # f.write('  CONSTRAINT `FK_BD5B077BF8F2EE9` FOREIGN KEY (`ct_carte_grise_id_id`) REFERENCES `ct_carte_grise` (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        # f.write("INSERT IGNORE INTO " + table_name + " (`id`, ``) VALUES")
        # i = 0
        # for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            # if(i > 0):
                # f.write(",") 
            # f.write("%s" % str(row))
            # i = i + 1
        # f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtAutreTarif():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_autre_tarif`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = ""
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_usage_imprime_technique_id_id` int(11) NOT NULL,')
        # f.write('  `aut_prix` double NOT NULL,')
        # f.write('  `aut_arrete` varchar(255) NOT NULL,')
        # f.write('  `aut_date` date DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  KEY `IDX_3BA0A4FE32BBA609` (`ct_usage_imprime_technique_id_id`),')
        # f.write('  CONSTRAINT `FK_3BA0A4FE32BBA609` FOREIGN KEY (`ct_usage_imprime_technique_id_id`) REFERENCES `ct_usage_imprime_technique` (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        # f.write("INSERT IGNORE INTO `ct_autre_tarif` (`id`, ``) VALUES")
        # i = 0
        # for row in rows:
            # lst_row = list(row)
            # lst_row[0] = 1
            # row = tuple(lst_row)
            # if(i > 0):
                # f.write(",") 
            # f.write("%s" % str(row))
            # i = i + 1
        # f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtArretePrix():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_arrete_prix`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_arrete_prix`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_user_id_id` int(11) DEFAULT NULL,')
        # f.write('  `art_numero` varchar(255) NOT NULL,')
        # f.write('  `art_date` date DEFAULT NULL,')
        # f.write('  `art_date_application` date DEFAULT NULL,')
        # f.write('  `art_created_at` date NOT NULL,')
        # f.write('  `art_updated_at` date DEFAULT NULL,')
        # f.write('  `art_observation` varchar(255) DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  KEY `IDX_1CEB5E02E4B6E02` (`ct_user_id_id`),')
        # f.write('  CONSTRAINT `FK_1CEB5E02E4B6E02` FOREIGN KEY (`ct_user_id_id`) REFERENCES `ct_user` (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO `ct_arrete_prix` (`id`, `ct_user_id_id`, `art_numero`, `art_date`, `art_date_application`, `art_created_at`, `art_updated_at`) VALUES")
        i = 0
        for row in rows:
            lst_row = list(row)
            if lst_row[3] is not None :
                lst_row[3] = lst_row[3].strftime("%Y-%d-%m")
            else:
                lst_row[3] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[4] is not None :
                lst_row[4] = lst_row[4].strftime("%Y-%d-%m")
            else:
                lst_row[4] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[5] is not None :
                lst_row[5] = lst_row[5].strftime("%Y-%d-%m")
            else:
                lst_row[5] = datetime.datetime.now().strftime("%Y-%d-%m")
            if lst_row[6] is not None :
                lst_row[6] = lst_row[6].strftime("%Y-%d-%m")
            else:
                lst_row[6] = datetime.datetime.now().strftime("%Y-%d-%m")
            row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtAnomalieType():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_anomalie_type`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_anomalie_type`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `atp_libelle` varchar(255) NOT NULL,')
        # f.write('  PRIMARY KEY (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE INTO `ct_anomalie_type` (`id`, `atp_libelle`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def RecuperationTableCtAnomalie():
    # The connect() constructor creates a connection to the MySQL server and returns a MySQLConnection object.
    table_name = '`ct_anomalie`'
    cnx = mysql.connector.connect(
        host=oldhost,
        database=olddb,
        user=olduser,
        password=oldpass
    )
    cursor = cnx.cursor()
    query = "SELECT * FROM `ct_anomalie`"
    cursor.execute(query)
    header = [row[0] for row in cursor.description]
    rows = cursor.fetchall()
    cnx.close()
    with open(filename, 'a') as f:
        # f.write('-- \n')
        # f.write('-- Structure de la table ' + table_name + '\n')
        # f.write('-- \n')
        # f.write(' \n')
        # f.write('DROP TABLE IF EXISTS ' + table_name+ ';\n')
        # f.write('/*!40101 SET @SAVED_CS_CLIENT = @@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = utf8 */;\n')
        # f.write('CREATE TABLE ' + table_name + ' (\n')
        # f.write('  `id` int(11) NOT NULL AUTO_INCREMENT,')
        # f.write('  `ct_anomalie_type_id_id` int(11) NOT NULL,')
        # f.write('  `anml_libelle` varchar(255) NOT NULL,')
        # f.write('  `anml_code` varchar(255) NOT NULL,')
        # f.write('  `anml_niveau_danger` int(11) DEFAULT NULL,')
        # f.write('  PRIMARY KEY (`id`),')
        # f.write('  KEY `IDX_E48094659A0FD0E5` (`ct_anomalie_type_id_id`),')
        # f.write('  CONSTRAINT `FK_E48094659A0FD0E5` FOREIGN KEY (`ct_anomalie_type_id_id`) REFERENCES `ct_anomalie_type` (`id`)')
        # f.write(') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT = @SAVED_CS_CLIENT */;\n')
        # f.write(' \n')
        
        f.write('-- \n')
        f.write('-- Contenu de la table : ' + table_name + '\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('LOCK TABLES ' + table_name + ' WRITE;\n')
        f.write('/*!40000 ALTER TABLE ' + table_name + ' DISABLE KEYS */;\n')
        f.write("INSERT IGNORE  INTO `ct_anomalie` (`id`, `ct_anomalie_type_id_id`, `anml_libelle`, `anml_code`) VALUES")
        i = 0
        for row in rows:
            #lst_row = list(row)
            #lst_row[0] = 1
            #row = tuple(lst_row)
            if(i > 0):
                f.write(",") 
            f.write("%s" % str(row))
            i = i + 1
        f.write(";\n")
        f.write("/*!40000 ALTER TABLE " + table_name + " ENABLE KEYS */;\n")
        f.write("UNLOCK TABLES;\n")
        f.write("\n")
        f.write('-- --------------------------------------------------------\n')

def ImportDataBase():
    file = open(filename)
    sql = file.read()

    cnx = mysql.connector.connect(user=newuser, password=newpass, host=newhost, database=newdb)
    cursor = cnx.cursor()

    for result in cursor.execute(sql, multi=True):
      if result.with_rows:
        print("Rows produced by statement '{}':".format(
          result.statement))
        print(result.fetchall())
      else:
        print("Number of rows affected by statement '{}': {}".format(
          result.statement, result.rowcount))

    cnx.close()
    

def executeScriptsFromFile():
    cnx = mysql.connector.connect(user=newuser, password=newpass, host=newhost, database=newdb)
    cursor = cnx.cursor()
    # Open and read the file as a single buffer
    fd = open(filename, 'r')
    sqlFile = fd.read()
    fd.close()
    # all SQL commands (split on ';')
    sqlCommands = sqlFile.split(';')
    # Execute every command from the input file
    for command in sqlCommands:
        # This will skip and report errors
        # For example, if the tables do not yet exist, this will skip over
        # the DROP TABLE commands
        try:
          if command.rstrip() != '':
            cursor.execute(command)
        except:
            print("Command skipped")
    cnx.commit()
    
def Recuperation():
    print("Début de l'extraction de la base de donnée")
    with open(filename, 'w') as f:
        f.write('-- Python SQL Dump\n')
        f.write('-- Extraction de la base de donnée\n')
        f.write('-- Depuis le serveur encours\n')
        f.write('-- \n')
        f.write(' \n')
        f.write(' \n')
        f.write('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";\n')
        f.write('SET AUTOCOMMIT = 0;\n')
        f.write('START TRANSACTION;\n')
        f.write('SET time_zone = "+00:00";\n')
        f.write(' \n')
        f.write(' \n')
        # f.write('/*!40101 SET CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n')
        # f.write('/*!40101 SET CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n')
        # f.write('/*!40101 SET COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n')
        f.write('/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n')
        f.write('/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n')
        f.write('/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n')
        f.write('/*!40101 SET NAMES utf8 */;\n')
        f.write('/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n')
        f.write("/*!40103 SET TIME_ZONE='+00:00' */;\n")
        f.write('/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n')
        f.write('/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n')
        f.write("/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n")
        f.write('/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n')
        f.write(' \n')
        f.write('-- \n')
        f.write('-- Base de données : `' + olddb + '`\n')
        f.write('-- \n')
        f.write(' \n')
        f.write('-- --------------------------------------------------------\n')
        f.write(' \n')
    #RecuperationTableCtUsageImprimeTechnique()
    #RecuperationTableCtHistoriqueType()
    #RecuperationTableCtGenreTarif()
    #RecuperationTableCtAutreVente()
    #RecuperationTableCtAutreTarif()
    #RecuperationTableCtExtraVente()
    #RecuperationTableCtPhoto()
    
    # RecuperationTableCtAutre()
    # RecuperationTableCtAutreDonne()
    # RecuperationTableCtZoneDesserte()
    # RecuperationTableCtProvince()
    # RecuperationTableCtArretePrix()
    # RecuperationTableCtTypeVisite()
    # RecuperationTableCtTypeUsage()
    # RecuperationTableCtTypeReception()
    # RecuperationTableCtTypeDroitPTAC()
    # RecuperationTableCtMotif()
    RecuperationTableCtVisiteExtra()
    # RecuperationTableCtVisiteExtraTarif()
    # RecuperationTableCtAnomalieType()
    # RecuperationTableCtUtilisation()
    # RecuperationTableCtSourceEnergie()
    # RecuperationTableCtConstAvDedType()
    # RecuperationTableCtUsageTarif()
    # RecuperationTableCtUsage()
    # RecuperationTableCtRole()
    # RecuperationTableCtProcesVerbal()
    # RecuperationTableCtMotifTarif()
    # RecuperationTableCtMarque()
    # RecuperationTableCtImprimeTech()
    # RecuperationTableCtGenreCategorie()
    # RecuperationTableCtGenre()
    # RecuperationTableCtDroitPTAC()
    # RecuperationTableCtCentre()
    # RecuperationTableCtCarrosserie()
    # RecuperationTableCtUser()
    # RecuperationTableCtBordereau()
    # RecuperationTableCtAnomalie()
    # RecuperationTableCtVehicule()
    # RecuperationTableCtReception()
    # RecuperationTableCtCarteGrise()
    # RecuperationTableCtVisite()
    # RecuperationTableCtVisiteCtAnomalie()
    # RecuperationTableCtVisiteCtVisiteExtra()
    # RecuperationTableCtConstAvDedCtConstAvDedCarac()
    # RecuperationTableCtConstAvDedCarac()
    # RecuperationTableCtConstAvDed()
    # RecuperationTableCtHistorique()
    # RecuperationTableCtImprimeTechUse()
    with open(filename, 'a') as f:
        f.write(' \n')
        f.write('COMMIT;\n')
        f.write(' \n')
        f.write('/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n')
        f.write('/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n')
        f.write('/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n')
        f.write('/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n')
        f.write('/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n')
        f.write('/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n')
        f.write('/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n')
        f.write('/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n')
        f.write(' \n')
        f.write('-- Dump Completed on ' + str(datetime.datetime.now()) + '\n')
    print("Fin de l'extraction de la base de donnée")
    
def Insertion():
    print("Début de l'insertion de la base de donnée")
    # command = "mysqldump −h " + newhost + " −u " + newuser + " −p" + newpass + " " + newdb + " < " + filename
    # command = f"mysqldump −h{newhost} −u {newuser} −p{newpass} {newdb} < {filename}"
    #command = f"mysqldump −h{newhost} −u {newuser} −p{newpass} {newdb} < {filename}"
    #os.system(command)
    #os.popen(command)
    #subprocess.Popen(command, shell=True)
    # subprocess.run(
        # [
            # "mysqldump",
            # "-u",
            # newuser,
            # "-p" + newpass,
            # newdb,
            # "<",
            # filename,
        # ]
    # )
    
    db = MySQLdb.connect(host=newhost,    # your host, usually localhost 
                         user=newuser,         # your username 
                         passwd=newpass,  # your password 
                         db=newdb)        # database name 
    # create an object to execute queries 
    cur = db.cursor() 
    # Open and read the file as a single buffer 
    fd = open(filename, 'r') 
    sqlFile = fd.read() 
    fd.close() 
    # all SQL commands (split on ';') 
    sqlCommands = sqlFile.split(';') 
    # Execute every command from the input file 
    for command in sqlCommands: 
        try: 
            c.execute(command) 
        except msg: 
            print ("Command skipped: ", msg )
        #ImportDataBase()
        #executeScriptsFromFile()
        print("Fin de l'insertion de la base de donnée")
    

Recuperation()
#Insertion()