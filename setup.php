<?php

// Securitate pentru rulare directa a scriptului
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Instalare
function setrio_bizcal_install()
{
    global $wpdb;
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Specialitati medicale
    $tblMedicalSpecialities = $wpdb->prefix."bizcal_medical_specialities";
    $sql = "CREATE TABLE $tblMedicalSpecialities (
        id_medical_speciality int(11) NOT NULL AUTO_INCREMENT,
        speciality_code varchar(50) NOT NULL,
        speciality_name varchar(255) NOT NULL,
        start_date datetime NOT NULL,
        end_date datetime NULL,
        PRIMARY KEY  (id_medical_speciality)
        ) $charset_collate;";
    dbDelta($sql);
    
    // Servicii medicale
    $tblMedicalServices = $wpdb->prefix."bizcal_medical_services";
    $sql = "CREATE TABLE $tblMedicalServices (
        id_medical_service int(11) NOT NULL AUTO_INCREMENT,
        id_medical_speciality int(11) NOT NULL,
        service_uid varchar(50) NOT NULL,
        service_name varchar(255) NOT NULL,
        start_date datetime NOT NULL,
        end_date datetime NULL,
        min_price decimal(20,2) NULL,
        max_price decimal(20,2) NULL,
        PRIMARY KEY  (id_medical_service)    
        ) $charset_collate;";
    dbDelta($sql);
    $wpdb->query("ALTER TABLE $tblMedicalServices ADD CONSTRAINT
                      fk_medical_services_speciality FOREIGN KEY (id_medical_speciality) REFERENCES $tblMedicalSpecialities (id_medical_speciality)");
    
    // Medici
    $tblPhysicians = $wpdb->prefix."bizcal_physicians";
    $sql = "CREATE TABLE $tblPhysicians (
        id_physician int(11) NOT NULL AUTO_INCREMENT,
        id_medical_speciality int(11) NOT NULL,
        physician_uid varchar(50) NOT NULL,
        physician_name varchar(255) NOT NULL,
        start_date datetime NOT NULL,
        end_date datetime NULL,
        PRIMARY KEY  (id_physician)
        ) $charset_collate;";
    dbDelta($sql);
    $wpdb->query("ALTER TABLE $tblPhysicians ADD CONSTRAINT
                      fk_physicians_speciality FOREIGN KEY (id_medical_speciality) REFERENCES $tblMedicalSpecialities (id_medical_speciality)");
    
    // Detalii medici
    $tblPhysiciansDescription = $wpdb->prefix."bizcal_physicians_description";
    $sql = "CREATE TABLE $tblPhysiciansDescription (
        physician_uid varchar(50) NOT NULL,
        physician_name varchar(255) NOT NULL,
        physician_picture_id int NULL,
        description varchar(5000) NULL,
        PRIMARY KEY  (physician_uid)
        ) $charset_collate;";
    dbDelta($sql);
    
    // Preturi
    $tblPrices = $wpdb->prefix."bizcal_prices";
    $sql = "CREATE TABLE $tblPrices (
        id_price int(11) NOT NULL AUTO_INCREMENT,
        id_physician int(11) NOT NULL,
        id_medical_service int(11) NOT NULL,
        price decimal(20,2) NOT NULL,
        start_date datetime NOT NULL,
        end_date datetime NULL,
        PRIMARY KEY  (id_price)
        ) $charset_collate;";
    dbDelta($sql);
    $wpdb->query("ALTER TABLE $tblPrices ADD CONSTRAINT
                      fk_prices_physician FOREIGN KEY (id_physician) REFERENCES $tblPhysicians (id_physician)");
    $wpdb->query("ALTER TABLE $tblPrices ADD CONSTRAINT
                      fk_prices_services FOREIGN KEY (id_medical_service) REFERENCES $tblMedicalServices (id_medical_service)");
    
    // Jurnal
    $tblLog = $wpdb->prefix."bizcal_request_log";
    $sql = "CREATE TABLE $tblLog (
        id_request_log int(11) NOT NULL AUTO_INCREMENT,
        request_type VARCHAR(50) NOT NULL,
        request_send_date DATETIME NOT NULL,
        request_response_date DATETIME NULL,
        message TEXT NOT NULL,
        response TEXT NULL,
        ip VARCHAR(40) NULL,
        PRIMARY KEY  (id_request_log)
        ) $charset_collate;";
    dbDelta($sql);
    
    // Actualizare versiune
    add_option('setrio-bizcalendar-db-version', '1.0');
}

// Dezactivare
function setrio_bizcal_deactivate()
{
    global $wpdb;
    
    $tblPrices = $wpdb->prefix."bizcal_prices";
    $wpdb->query("ALTER TABLE $tblPrices DROP FOREIGN KEY fk_prices_physician");
    $wpdb->query("ALTER TABLE $tblPrices DROP FOREIGN KEY fk_prices_services");
    
    $tblPhysicians = $wpdb->prefix."bizcal_physicians";
    $wpdb->query("ALTER TABLE $tblPhysicians DROP FOREIGN KEY fk_physicians_speciality");
    
    $tblMedicalServices = $wpdb->prefix."bizcal_medical_services";
    $wpdb->query("ALTER TABLE $tblMedicalServices DROP FOREIGN KEY fk_medical_services_speciality");
}

?>
