
USE db_banco_adso;

INSERT INTO clientes(nombre) VALUES
('Jaider Gil'),
('María Rodríguez'),
('Ana Gómez'),
('Luis Torres'),
('Sofia Martínez');

INSERT INTO cuentas (numero_cuenta, saldo, clientes_id) VALUES
('1001', 500000.00, 1),
('1002', 750000.00, 2),
('1003', 1200000.00, 3),
('1004', 300000.00, 4),
('1005', 950000.00, 5);

-- 1001 1234567
-- 1002 123456
-- 1003 1234
-- 1004 12345
-- 1005 123456789

INSERT INTO usuarios(cuenta_id, clave_hash) VALUES
(1, '$argon2id$v=19$m=65536,t=4,p=1$b3BoMVFmWm5KYWM5a3RkWA$wF77r8WjCrS2nhx4kszWq5tfWBdnE0ejGuiPBbpk0gc' ),
(2, '$2y$12$Ms7jC7MD8X2uTS7/WTGUvO9THO7n1R5ayAIe2/WSaCSZYmHSGUKNC'),
(3, '$2y$12$LZ1D2YpZ0Nsd88RjDB7XA.1cHf8n2./eic5sQgXCWHzZU46gtpl9i'),
(4, '$2y$12$aPb87.PnysFGqeXRdlERO.z50UaUtv6nwuGJ2BP9O3hCJvdFaRzw2'),
(5, '$2y$12$WK0fb6PD9HkdTvUKy.AwFuHlRhhqGtn0dnTxg.8Y7m454JU9WWAtS');
