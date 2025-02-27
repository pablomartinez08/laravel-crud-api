--
-- PostgreSQL database dump
--

-- Dumped from database version 16.4
-- Dumped by pg_dump version 16.4

-- Started on 2025-02-27 19:53:01

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4903 (class 0 OID 263469)
-- Dependencies: 221
-- Data for Name: sports; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.sports VALUES (1, 'Tennis', 'Un deporte de raqueta y pelota que se juega en una pista.', '2025-02-24 21:10:03', '2025-02-24 21:10:03');
INSERT INTO public.sports VALUES (2, 'Padel', 'Deporte similar al tenis, pero se juega en una pista más pequeña con paredes.', '2025-02-24 21:10:03', '2025-02-24 21:10:03');
INSERT INTO public.sports VALUES (3, 'Football', 'Deporte en el que dos equipos compiten para marcar goles.', '2025-02-24 21:10:03', '2025-02-24 21:10:03');
INSERT INTO public.sports VALUES (4, 'Squash', NULL, '2025-02-27 19:15:39', '2025-02-27 19:15:39');
INSERT INTO public.sports VALUES (5, 'Golf', NULL, '2025-02-27 19:16:02', '2025-02-27 19:16:02');


--
-- TOC entry 4907 (class 0 OID 263492)
-- Dependencies: 225
-- Data for Name: courts; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.courts VALUES (1, 'Court 1 - Tennis', 1, '2025-02-24 21:10:16', '2025-02-24 21:10:16');
INSERT INTO public.courts VALUES (2, 'Court 2 - Tennis', 1, '2025-02-24 21:10:16', '2025-02-24 21:10:16');
INSERT INTO public.courts VALUES (3, 'Court 1 - Padel', 2, '2025-02-24 21:10:16', '2025-02-24 21:10:16');
INSERT INTO public.courts VALUES (4, 'Court 1 - Football', 3, '2025-02-24 21:10:16', '2025-02-25 16:00:51');
INSERT INTO public.courts VALUES (5, 'Court 1 - Squash', 4, '2025-02-27 19:18:48', '2025-02-27 19:18:48');


--
-- TOC entry 4909 (class 0 OID 263504)
-- Dependencies: 227
-- Data for Name: members; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.members VALUES (1, 'Pedro López', 'pedro.actualizado@example.com', '123456788', '2025-02-25 21:10:36', '2025-02-26 10:17:02');
INSERT INTO public.members VALUES (2, 'Susana Ruiz', 'susana.ruiz@example.com', '123456789', '2025-02-27 19:19:47', '2025-02-27 19:19:47');
INSERT INTO public.members VALUES (3, 'Fernando Mata', 'fernando.mata@example.com', '123456778', '2025-02-27 19:20:20', '2025-02-27 19:20:20');
INSERT INTO public.members VALUES (4, 'Laura Vera', 'laura.vera@example.com', '123456790', '2025-02-27 19:20:55', '2025-02-27 19:20:55');
INSERT INTO public.members VALUES (5, 'Juan Cuesta', 'juan.cuesta@example.com', '123456700', '2025-02-27 19:21:18', '2025-02-27 19:21:18');


--
-- TOC entry 4898 (class 0 OID 263102)
-- Dependencies: 216
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.migrations VALUES (18, '2025_02_24_203855_update_sport_id_nullable_in_courts', 1);
INSERT INTO public.migrations VALUES (19, '0001_01_01_000000_create_users_table', 2);
INSERT INTO public.migrations VALUES (20, '0001_01_01_000001_create_cache_table', 2);
INSERT INTO public.migrations VALUES (21, '0001_01_01_000002_create_jobs_table', 2);
INSERT INTO public.migrations VALUES (22, '2025_02_18_100000_create_sports_table', 2);
INSERT INTO public.migrations VALUES (23, '2025_02_18_155021_create_personal_access_tokens_table', 2);
INSERT INTO public.migrations VALUES (24, '2025_02_18_163635_create_courts_table', 2);
INSERT INTO public.migrations VALUES (25, '2025_02_18_163636_create_members_table', 2);
INSERT INTO public.migrations VALUES (26, '2025_02_18_163636_create_reservations_table', 2);
INSERT INTO public.migrations VALUES (27, '2025_02_24_205414_modify_sport_id_in_courts_table', 2);


--
-- TOC entry 4905 (class 0 OID 263480)
-- Dependencies: 223
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.personal_access_tokens VALUES (7, 'App\Models\User', 2, 'authToken', '756a1f3dcef3073d01b28c0a2552321954b9aed6d2eb2beb9394b570eb77e64e', '["*"]', NULL, NULL, '2025-02-25 19:53:59', '2025-02-25 19:53:59');
INSERT INTO public.personal_access_tokens VALUES (9, 'App\Models\V1\User', 3, 'authToken', 'd3b5b63bc55c1200c36235a89a79de106554632475af2dfeef0821181d9ec87c', '["*"]', '2025-02-27 19:24:19', NULL, '2025-02-27 17:08:49', '2025-02-27 19:24:19');
INSERT INTO public.personal_access_tokens VALUES (10, 'App\Models\V1\User', 2, 'authToken', 'b2d733d3cdf8be4b841ef01b1776812469098e627b8266770b5b6c4fa53d660f', '["*"]', NULL, NULL, '2025-02-27 19:28:27', '2025-02-27 19:28:27');
INSERT INTO public.personal_access_tokens VALUES (11, 'App\Models\V1\User', 1, 'authToken', '17991a9b3835dfe01b635314c4666b3bdda14cc108c03dc9051450e78639fdcb', '["*"]', '2025-02-27 19:33:19', NULL, '2025-02-27 19:31:30', '2025-02-27 19:33:19');
INSERT INTO public.personal_access_tokens VALUES (12, 'App\Models\V1\User', 2, 'authToken', '335c66506b6c49142cdf0b3cd251a872e90b988d5b6962e78b6ed46415f56ddf', '["*"]', NULL, NULL, '2025-02-27 19:35:00', '2025-02-27 19:35:00');
INSERT INTO public.personal_access_tokens VALUES (13, 'App\Models\V1\User', 3, 'authToken', '7562682a729a2a662eb9258912c71d0fd79dcadf801f1774333818376ca71ce8', '["*"]', NULL, NULL, '2025-02-27 19:35:37', '2025-02-27 19:35:37');
INSERT INTO public.personal_access_tokens VALUES (14, 'App\Models\V1\User', 4, 'authToken', '3a682c5bd387684b560684b2d41f10faeb2540e9603acf83c5e41529fdd9803d', '["*"]', NULL, NULL, '2025-02-27 19:36:07', '2025-02-27 19:36:07');
INSERT INTO public.personal_access_tokens VALUES (15, 'App\Models\V1\User', 5, 'authToken', '3c54eb4eec1fe329370b2d1ad5f506ee10192aa5ea9262758ff53d0127600f71', '["*"]', NULL, NULL, '2025-02-27 19:36:25', '2025-02-27 19:36:25');
INSERT INTO public.personal_access_tokens VALUES (16, 'App\Models\V1\User', 6, 'authToken', '0dca8171640214c3c28b90d1818f24e3239cfc682acae9d365cb358c3adfc48c', '["*"]', '2025-02-27 19:50:04', NULL, '2025-02-27 19:39:08', '2025-02-27 19:50:04');
INSERT INTO public.personal_access_tokens VALUES (8, 'App\Models\User', 2, 'authToken', '56b45d7dd5a503f0c3dc2bd9c866b44b683db7309f0576eeb0bc6c2477d1a3e6', '["*"]', '2025-02-26 19:50:05', NULL, '2025-02-25 21:10:03', '2025-02-26 19:50:05');
INSERT INTO public.personal_access_tokens VALUES (6, 'App\Models\User', 2, 'authToken', '39c0b154ea25bf637830032877d862844379466b7d41bab27c1bb04c3f402e12', '["*"]', '2025-02-27 12:05:13', NULL, '2025-02-25 19:53:26', '2025-02-27 12:05:13');


--
-- TOC entry 4911 (class 0 OID 263517)
-- Dependencies: 229
-- Data for Name: reservations; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.reservations VALUES (1, 2, 1, '2025-02-28', '10:00:00', '2025-02-26 11:12:59', '2025-02-26 11:12:59');
INSERT INTO public.reservations VALUES (2, 2, 1, '2025-02-28', '11:00:00', '2025-02-26 11:50:44', '2025-02-26 11:50:44');
INSERT INTO public.reservations VALUES (3, 2, 1, '2025-02-27', '08:00:00', '2025-02-26 12:06:32', '2025-02-26 18:51:57');
INSERT INTO public.reservations VALUES (4, 1, 1, '2025-02-28', '09:00:00', '2025-02-26 19:31:47', '2025-02-26 19:31:47');


--
-- TOC entry 4901 (class 0 OID 263416)
-- Dependencies: 219
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.sessions VALUES ('oSTCJIljoJsMI38mvfZoEnCAK36BXYq7XZrgc0WU', NULL, '127.0.0.1', 'PostmanRuntime/7.43.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2VMbU9GNXlwQjFVTGdxUnlrYmNtZzdBSjVpTHVTVW9vOWNPQ0NzRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740500591);


--
-- TOC entry 4900 (class 0 OID 263399)
-- Dependencies: 218
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.users VALUES (1, 'Pedro López', 'pedro.actualizado@example.com', NULL, '$2y$12$tndHLn.KamQZVLX0x5TxcuILa5jmKa5NqQOJ.R5Sj0ls1PQ5mLLgi', NULL, '2025-02-27 19:31:30', '2025-02-27 19:33:20');
INSERT INTO public.users VALUES (2, 'Susana Ruiz', 'susana.ruiz@example.com', NULL, '$2y$12$AGr1Tb6SzY0.TYdpTnb2Xe4KCWxOHU0GnmXkRbvFU5iM2/SqZgcRW', NULL, '2025-02-27 19:35:00', '2025-02-27 19:35:00');
INSERT INTO public.users VALUES (3, 'Fernando Mata', 'fernando.mata@example.com', NULL, '$2y$12$Nnb3Uje89C9nXg4AbfvSMuswTFDhXyFTM6RX8lF4eb6bvy1ZlwL5.', NULL, '2025-02-27 19:35:37', '2025-02-27 19:35:37');
INSERT INTO public.users VALUES (4, 'Laura Vera', 'laura.vera@example.com', NULL, '$2y$12$nQCUEtdY7/cDWK73JnNN..P103Qq4NU6FHLVkLpElg5W62xIKA41K', NULL, '2025-02-27 19:36:07', '2025-02-27 19:36:07');
INSERT INTO public.users VALUES (5, 'Juan Cuesta', 'juan.cuesta@example.com', NULL, '$2y$12$2cIoaxvj9.Y1qV3o99PYEOvpjpqs4l2xU.mkRWOTnV5bUj0yEUojC', NULL, '2025-02-27 19:36:25', '2025-02-27 19:36:25');


--
-- TOC entry 4917 (class 0 OID 0)
-- Dependencies: 224
-- Name: courts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.courts_id_seq', 5, true);


--
-- TOC entry 4918 (class 0 OID 0)
-- Dependencies: 226
-- Name: members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.members_id_seq', 5, true);


--
-- TOC entry 4919 (class 0 OID 0)
-- Dependencies: 215
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 27, true);


--
-- TOC entry 4920 (class 0 OID 0)
-- Dependencies: 222
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 16, true);


--
-- TOC entry 4921 (class 0 OID 0)
-- Dependencies: 228
-- Name: reservations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reservations_id_seq', 4, true);


--
-- TOC entry 4922 (class 0 OID 0)
-- Dependencies: 220
-- Name: sports_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sports_id_seq', 5, true);


--
-- TOC entry 4923 (class 0 OID 0)
-- Dependencies: 217
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 5, true);


-- Completed on 2025-02-27 19:53:01

--
-- PostgreSQL database dump complete
--

