

drop DATABASE db_banco_adso;
CREATE DATABASE db_banco_adso charset utf8mb4 collate utf8mb4_general_ci;
use db_banco_adso;

create table clientes(id  INT AUTO_INCREMENT primary key , 
nombre varchar(50) not null) engine = innoDB default charset=utf8mb4;

CREATE TABLE cuentas (id INT AUTO_INCREMENT PRIMARY KEY , numero_cuenta VARCHAR(50) NOT NULL UNIQUE ,
saldo DECIMAL(12,2) NOT NULL DEFAULT 00.0 , clientes_id INT ,
CONSTRAINT fk_clientes_id FOREIGN KEY (clientes_id)
REFERENCES clientes(id) ON DELETE RESTRICT 
) ENGINE=innoDB DEFAULT CHARSET = utf8mb4;

CREATE TABLE usuarios(id INT AUTO_INCREMENT PRIMARY KEY,
cuenta_id INT,
clave_hash VARCHAR(255) not null,
CONSTRAINT fk_cuenta_id FOREIGN KEY (cuenta_id)
REFERENCES cuentas(id) ON DELETE RESTRICT ) ENGINE=innoDB CHARSET = utf8mb4;

CREATE TABLE retiros(id INT AUTO_INCREMENT PRIMARY KEY , 
cuentas_id INT ,
valor DECIMAL(12,2) NOT NULL,
fecha DATETIME DEFAULT current_timestamp,
constraint fk_cuentas_id_retiros FOREIGN KEY (cuentas_id)
REFERENCES  cuentas(id) ON  DELETE RESTRICT )ENGINE = innoDB CHARSET = utf8mb4;

CREATE TABLE transferencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cuenta_origen_id INT NOT NULL,
    cuenta_destino_id INT NOT NULL,
    valor DECIMAL(12,2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_trans_origen FOREIGN KEY (cuenta_origen_id) 
        REFERENCES cuentas(id) 
        ON DELETE RESTRICT,
    CONSTRAINT fk_trans_destino FOREIGN KEY (cuenta_destino_id) 
        REFERENCES cuentas(id) 
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;