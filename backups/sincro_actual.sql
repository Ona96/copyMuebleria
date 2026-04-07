--
-- PostgreSQL database dump
--

\restrict Pr8hT9tw7xIBG2knu2pLyTtdbCbaOMjTcq925L5ayp0UCXaRn3LJy5z8gnDqP52

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: bancos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.bancos (numero, nombre, comentario, created_at, updated_at) FROM stdin;
4	Macro	\N	2026-01-28 02:17:05	2026-01-28 02:17:05
1	Nación	\N	2026-01-26 16:01:57	2026-01-27 23:37:35
2	ICBC	\N	2026-01-27 23:37:35	2026-01-27 23:37:35
3	Macro	\N	2026-01-28 01:35:51	2026-01-27 23:37:35
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: cuentas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cuentas (codigo, numero_banco, numero_cuenta, created_at, updated_at) FROM stdin;
1	1	41/67	\N	\N
2	1	93/18	\N	\N
4	2	828/56	\N	\N
5	3	25/24	\N	\N
\.


--
-- Data for Name: proveedores; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.proveedores (codigo, numero_proveedor, nombre, direccion, telefono, localidad, provincia, fecha_ultima_compra, created_at, updated_at, activo) FROM stdin;
1	1	Macoser	\N	\N	\N	\N	\N	\N	\N	t
3	3	Dorazi e Hijos SA	Posadas	03764-433991	Santa Fe (3000)	Santa Fe	\N	\N	\N	t
4	4	Industrias BK	Tomas Cabrera 4818	0341-4643872	Rosario (2000)	Santa Fe	\N	\N	\N	t
5	5	Torres e Hijos	Posadas	75235601	Posadas (3300)	\N	\N	\N	\N	t
6	6	Keller Motos	Suipacha 1330	30-709568589	V. Bosch (1628)	Cordoba	\N	\N	\N	t
7	7	ByD Sudamericana SA	Carlos M Ramirez 1434	011-49192000	Bs. As. (1437)	Bs. As.	\N	\N	\N	t
8	8	Electro Oulet SRL	Av. Mosconi 3632	5263336	1419-CABA	Bs. As.	\N	\N	\N	t
9	9	Transp. Sanaider SRL	Ruta 12 KM 7.5	0376-4481600	3300-Posadas	Misiones	\N	\N	\N	t
11	11	Bambi SA	Callao 7100	0341-4620000	Rosario (2000)	Santa Fe	\N	\N	\N	t
12	12	Corven Motors ARG. SA	Marcos Ciani 2118	03462-409030	2600-V. Tuerto	Santa Fe	\N	\N	\N	t
13	13	Radio Victoria Fueguina	J. D. Peron 2823	87-0056	\N	\N	\N	\N	\N	t
14	14	Todo Living SRL	Posadas	03764-480197	\N	\N	\N	\N	\N	t
15	15	Kalfrix Brasca	Parana-E. Rios	011-46294933	1706-Haedo	Bs. As.	\N	\N	\N	t
16	16	Marmicoc Arg. S.A.	Roseti 1192/1212	011-45516081	1427-Bs. As.	\N	\N	\N	\N	t
17	17	Ceven SA	M. Garcia 352	011-40101600	1284-CABA	Bs. As.	\N	\N	\N	t
18	18	Triada Tec SRL	Carlos A Lopez 3648	011-45013939	1419-CABA	Bs. As.	\N	\N	\N	t
19	19	Tenacta SA	Girardot 1449	011-45547444	1427-Capital	Bs. As.	\N	\N	\N	t
20	20	Groupe Seb ARG. SA	Gral Hornos 1720/22	011-40165000	1272-Bs. As.	\N	\N	\N	\N	t
21	21	Candy Elec. Argent SA	Av. Luis M. Campos 1061	011-47787300	1426-CABA	Bs. As.	\N	\N	\N	t
22	22	Rodados Energy SRL	Gorostiaga 3183	30-710737815	Santa Fe 	S. Fe	\N	\N	\N	t
23	23	Hector Codini SA	S. Francisco-CBA.	56427607	\N	\N	\N	\N	\N	t
24	24	Todomusica SA	Estomba 243	011-45553434	1427- Buenos Aires	Bs. As.	\N	\N	\N	t
25	25	Jose M. Alladio E HS. SA	Cordoba 325	03573-489200	Luque (5967)	Cordoba	\N	\N	\N	t
26	26	Prola Juan C.	Rafaela-S. Fe	49232871	\N	\N	\N	\N	\N	t
27	27	Briket SA	Ov. Lagos 7245	341558343	2000-Rosario 	Santa Fe	\N	\N	\N	t
28	28	Durafort SRL	B. Sur Mer 3334	0381-4239465	4000-Tucuman	Tucuman	\N	\N	\N	t
29	29	La Emilia SA	Tucuman 3550	011-8653879	Cap. Federal	\N	\N	\N	\N	t
30	30	Kronen Interac. SA	Haedo-Bs. As.	4891587	\N	\N	\N	\N	\N	t
31	31	Red del Interior	\N	\N	Rosario 	Santa Fe	\N	\N	\N	t
32	32	Lusqtoff ARG. SA	Udaondo 1448	2374685261	CABA	Bs. As.	\N	\N	\N	t
33	33	Nexina SA	Florida 537	\N	1005-CABA	Bs. As.	\N	\N	\N	t
34	34	Eldorado SA	Ruta Nac. 34 44 S. Grand S. F	0054-3476494	2142-S. Fe	S. Fe	\N	\N	\N	t
35	35	Orbis Mertig Saic	Yerbal 1200	735-5300	V. Adelina (1607)	Bs. As.	\N	\N	\N	t
36	36	New San	Roque Perez 3650	545-5100	C. Federal (1430)	Bs. As.	\N	\N	\N	t
37	37	Com. Alpaca SA	Tomas M. de Anchorena 655	011-48671001	1170-Cap. Fed.	Bs. As.	\N	\N	\N	t
38	38	Metal. Tuyu SRL	Escultor Parodi 5331	011-47341834	1678-Caseros	Bs. As.	\N	\N	\N	t
39	39	Horacio D. Ferrari SA	Centenera 450	011-46624105	Huringhan	Bs. As.	\N	\N	\N	t
40	40	Elect. System SA	R. Perez 3650	4545-5100	C. Fed. (1430)	Bs. As.	\N	\N	\N	t
41	41	Dakot SA	Gabino Ezeiza 8891	4739-4340	Loma Hermosa 	Bs. As. 1657	\N	\N	\N	t
42	42	Liliana SRL	Warnes 1155	041-541300	Rosario (2000)	S. Fe	\N	\N	\N	t
44	44	Surex Argentina SA	Av. H. Yrigoyen 2175	208-1285	Avellaneda 1870	Bs. As.	\N	\N	\N	t
45	45	Marina Sol 	Zavalia 675	0342-459320	3000-Santa Fe	Santa Fe	\N	\N	\N	t
46	46	Autosal SA	E. Echeverria 930	011-47603967	1604-Florida	Bs. As.	\N	\N	\N	t
47	47	Arimex Importadora SA	Andres Rolon 110 S. Isidoro	011-47073000	1642-C. A. Bs. As.	Bs. As.	\N	\N	\N	t
48	48	H. L. Catamarca SA	Neba	03743-420143	3334-P. Rico	Misiones	\N	\N	\N	t
49	49	Bidcom SRL	Caldas 1535	Gabriel P. K.	CABA	Bs. As.	\N	\N	\N	t
50	50	Equipar	Necochea 3161	0341-4850768	2000-Rosario 	Sa. Fe	\N	\N	\N	t
51	51	Frimetal SA	Rosario	\N	\N	\N	\N	\N	\N	t
52	52	Grupo Nucleo SA	Av. Brasil 3237	1151909999	1260 P. Patricios	CABA	\N	\N	\N	t
53	53	Avelino SA	R. Cial 21 KM 285	03402-498210	2126- P. Ester	S. Fe	\N	\N	\N	t
54	54	Hatsu Electronics SA	Sarmiento 1206	011-43824779	C. Fed- (1041)	Bs. As.	\N	\N	\N	t
55	55	Red Marquez SA	Azcuenaga 795	011-44890099	Moron (1708)	Bs. As.	\N	\N	\N	t
56	56	Andriher SRL	S. Martin 5795 1'B CABA	011-59399188	1416-Bs. As.	CABA	\N	\N	\N	t
57	57	Whirpool ARG. SA	Av. Crovara 2550	\N	1766-La Tablada	Bs. As.	\N	\N	\N	t
58	58	First Rate SA	Reconquista 336 6° Piso	011-46554444	1003-Cap. Fed	\N	\N	\N	\N	t
59	59	Panambi Muebles	Gdor. Barreyro 900	03755-421027	3360-Obera	Misiones	\N	\N	\N	t
60	60	Bienmme Argentina SA	J. Ingenieros 4202/84	011-44417604	1766-Tablada	Bs. As.	\N	\N	\N	t
61	61	Muebles Orlandi SRL	Independencia 342	3462470357	2732-Elortondo	S. Fe	\N	\N	\N	t
62	62	Vetas SRL 	Galvez 178	0341-4395264	2000-Rosario 	Santa Fe	\N	\N	\N	t
63	63	Kioto SA	Marcelo T. de Alvear 4492	011-46532103	1702-Ciudadela. 	Bs. As.	\N	\N	\N	t
64	64	C C Agro y Tec SA	Av. Libertador 264	011-48080133	CABA 1638	Bs. As.	\N	\N	\N	t
65	65	Zanella Hnos. y CIA SA	Juan Zanella 3347	011-47164007	1678-Caseros	Bs. As.	\N	\N	\N	t
66	66	Stark-Zelarrayan	Suipacha 1330	\N	Villa Bosch	Merlo- Bs. As.	\N	\N	\N	t
67	67	Visuar S. A.	B. Q. Martin 1924	011-41093000	1296-C. Federal	Bs. As.	\N	\N	\N	t
68	68	Calvo Jose A. 	Francia 1471	03752-457301	3300- Posadas	Misiones	\N	\N	\N	t
69	69	Bic. Futura SRL	Av. 12 Octubre 889	011-42700873	Quilmes 	Bs. As.	\N	\N	\N	t
70	70	Telso SA	German Abdala 2650	011-46250033	I. Casanova-1765	Bs. As.	\N	\N	\N	t
71	71	DRR Sistema. 	\N	\N	\N	\N	\N	\N	\N	t
2	2	Carpinteria Chule	Capiovi	3743-503494	\N	\N	\N	\N	\N	t
74	74	La Gioconda	Bs. As. 1244	0353-4535094	5900-Villa Mari	Cordoba	\N	\N	\N	t
75	75	Lusqtoff Argen. SA	Blas Franco 6878	3764456119	Posadas  	Misiones	\N	\N	\N	t
76	76	Escorial SA IyC	Av. 25 de Mayo 1400	011-47541122	1650-S. Martin	Bs. As.	\N	\N	\N	t
77	77	Gularo SA	Italia 569	011-47267800	1618-El Talar	Bs. As.	\N	\N	\N	t
78	78	Metal. La Toma SA	\N	\N	\N	\N	\N	\N	\N	t
79	79	Capyc- Godeco	Av. 25 de Mayo 850	03564-482001	2424-Devoto	Cordoba	\N	\N	\N	t
80	80	D. Me D. SRL	Rosario KM 284	0341-3179000	2124-S. Fe	S. Fe	\N	\N	\N	t
81	81	Carrier	\N	\N	\N	\N	\N	\N	\N	t
83	83	Marfil	Ruta 32 Acc. Sur Segui	34315611041	Segui	E. Rios	\N	\N	\N	t
84	84	Metalur. Crivel SC	Castagnino 1170	0341-4530888	2000-Rosario 	S. Fe	\N	\N	\N	t
85	85	Trimas Mario Liv	El Soberbio	3755509456	\N	\N	\N	\N	\N	t
86	86	Ferret. Gral Paz	Bv 25 de Mayo 55	3564461700	Freyre (2413)	Cordoba	\N	\N	\N	t
87	87	Carmain SRL	Valentin Virasoro 1977	1148544137	1414	Buenos Aires	\N	\N	\N	t
88	88	Argentrade SRL	Calle 514 2050	0221-4712323	1901-Ringelet	La Plata B	\N	\N	\N	t
89	89	Di Madera SRL	25 De Mayo 1225	3754423143	Leandro N. Alem	Misiones	\N	\N	\N	t
90	90	Grupo Simpa SA	Av. Col. Panamericana 1804	1147083300	1607- V. Adelina	Bs. As.	\N	\N	\N	t
91	91	Someco Electron. SA	Saens Peña 788 Piso 9	30702179153	\N	\N	\N	\N	\N	t
92	92	Mogueta Jose	Bolivar 1450	3471426689	Cañada de Gomez	S. Fe	\N	\N	\N	t
93	93	Eclipse-Vandevelde	Dr. Galvez 1524	3496421597	3080-Esperanza	S. Fe	\N	\N	\N	t
94	94	Genoud	Frondizi 1150	2477437700	Pergamino 2700	Bs. As.	\N	\N	\N	t
95	95	Fornax SRL	Rueda 1538/62	3414825205	2000-Rosario 	Santa Fe	\N	\N	\N	t
96	96	Microbell SA	Cnel. Ramon Lista 5168	1146499050	1417-CABA	\N	\N	\N	\N	t
97	97	Akan SA	Av. Belgrano 2804	1147271133	1611-Don Torcua	Bs. As.	\N	\N	\N	t
98	98	Casablanca B. Bahia SA	Bahia Blanca 1355 2° Piso	\N	1407-CABA	Bs. As.	\N	\N	\N	t
99	99	Cuyoplacas SA	Acc. Norte KM 5.2	\N	Las Heras	Mendoza	\N	\N	\N	t
100	100	Centrex	Tres Cruces 4373/75	Laferrere	\N	Bs. As.	\N	\N	\N	t
101	101	Fernandez Jorge	Soberbio	3755509456	\N	\N	\N	\N	\N	t
102	102	Multipoint SA	Cordoba 4860 CABA-1414	1143259000	Bs. As.  	\N	\N	\N	\N	t
103	103	Ing. Plast. Rosario SA	Gdor. Galvez Santa Fe	\N	\N	\N	\N	\N	\N	t
104	104	Comp/En Grupo .NET	Maipu 479	3514265060	Cordoba	Cordoba	\N	\N	\N	t
105	105	Santich Y Cia SA	Ituzaingo 1186-CABA	1143004088	\N	\N	\N	\N	\N	t
106	106	Navarro Diego N. Neba	Gdor. Udaondo 2002 	1142405853	Lanus Oeste	Bs. As.	\N	\N	\N	t
107	107	Blue Rest-Graciano	Alsina 415 Piso 2 B	\N	Quilmes 	Bs. As.	\N	\N	\N	t
108	108	Grupo Que Luz	B. De Irigoyen	\N	\N	\N	\N	\N	\N	t
109	109	Isola Maria Gimena Cascos	Garghan 151	\N	Arroyo Seco	Santa Fe	\N	\N	\N	t
110	110	Latapy SRL	9 De Julio 350 T. De Pacheco	\N	1414-CABA	\N	\N	\N	\N	t
111	111	Happy Lio SRL	V. Lopez 277 (1640)	\N	Martines 	Bs. As.	\N	\N	\N	t
112	112	Ind. Madereras ARG. SA	Rafaela 	\N	\N	Santa Fe	\N	\N	\N	t
113	113	Dimashq ARG. SA	Julio Moreno 2305	C33711005779	1657-P. Podesta 	Bs. As.	\N	\N	\N	t
114	114	Stylo	Buen Viaje 636	C30712443746	Moron 	Bs. As.	\N	\N	\N	t
115	115	Danak SRL	Juan A. Garcia 2464	30708507519	CABA	\N	\N	\N	\N	t
116	116	Fag Sistems SA	S. Domingo 3220 (1892)	\N	CABA	\N	\N	\N	\N	t
117	117	Lilus SA	Camargo 644	\N	CABA	\N	\N	\N	\N	t
118	118	Leanval SA	F. Echelaine 675	\N	Rio Grande	T. Del Fuego	\N	\N	\N	t
119	119	Silfab SA	Campichuelo 103	1156813468w	CABA	\N	\N	\N	\N	t
120	120	Ind. Taesca SRL	O. Lagos 98	30-709274720	Rosario	S. Fe	\N	\N	\N	t
121	121	Tecnologia RDLF SA	M. ARG 130	2364427254	Junin	Bs. As.	\N	\N	\N	t
122	122	Conmetal ARG. SA	Calle 103 1152	33709749019	S. Martin 	Bs. As.	\N	\N	\N	t
123	123	Ludadel SRL	R. Balbin 2754	30715106457	\N	\N	\N	\N	\N	t
124	124	Hogaris Nova SA	Posadas 2559- Bs. As.	C30708477997	\N	\N	\N	\N	\N	t
125	125	Famiel SA	\N	\N	\N	\N	\N	\N	\N	t
126	126	Casablanca Mueblecharif	Rep-Or. Uruguay 1189	\N	CABA	\N	\N	\N	\N	t
127	127	Tecair SA	Warnes 596 CABA	\N	Raul De La Cruz	\N	\N	\N	\N	t
128	128	Memitex SA Amarelo	Loria 539/41 CABA	\N	Bs. As.	\N	\N	\N	\N	t
129	129	Athuel Electr. SA	\N	\N	\N	\N	\N	\N	\N	t
130	130	Big Stars SRL	Carcaraña	\N	\N	Santa Fe	\N	\N	\N	t
131	131	Gani SA	Sauce Viejo	\N	\N	S. Fe	\N	\N	\N	t
133	133	Kaibo SA	Av. Chiclana 3335	\N	CABA-1260	\N	\N	\N	\N	t
72	72	Buen Ayre	Moreno 2954	0341-4821948	2000-Rosario 	S. Fe	\N	\N	\N	t
10	10	Alar Comunicaciones SRL	Pinzon 1418 PBDT01	4774-1230	S. Martin	Cordoba	\N	\N	\N	t
43	43	Ricchezze Jorge A.	Paraguay 695	03471-426000	Cañada de Gomez	S. Fe	\N	\N	\N	t
73	73	Diemel SRL	Ayacucho 6113	3414639510	2000-Rosario 	S- Fe	\N	\N	\N	t
82	82	Eiffel Hogar SRL	Cosquin 2486	1146875218	CABA	Buenos Aires	\N	\N	\N	t
132	132	Starken ARG. SA	Pueyrredon 1238	\N	CABA	\N	\N	\N	\N	t
\.


--
-- Data for Name: facturas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.facturas (id, codigo_proveedor, fecha, numero, total, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cheques; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cheques (id, entro, codigo_cuenta, numero_cheque, fecha, debe, haber, codigo_factura, created_at, updated_at, fecha_entrada) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_06_07_224600_create_proveedores_table	1
5	2025_06_07_224616_create_facturas_table	1
6	2025_06_07_224625_create_bancos_table	1
7	2025_06_07_224635_create_cuentas_table	1
8	2025_06_07_224641_create_cheques_table	1
9	2025_06_29_224828_add_activo_to_proveedores_table	1
10	2025_06_29_230429_remove_tasa_sobretasa_from_facturas_table	1
11	2025_09_11_121222_add_fecha_entrada_to_cheques_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	Perez	perez@gmail.com	\N	$2y$12$en2jSusktgUX/UPTk8kn7OwBPpD3eniPVWfNPLH7sTrh1.pox2EaK	\N	2026-01-23 13:55:23	2026-01-27 23:37:35
2	Perez	Perez@gmail.com	\N	$2y$12$c5AsnEhgh1oHfo2fTHT3zeru0AxXY1/x.M1JZ3MbSs2GMHMuXhdFW	rr1cAEj9WLdkoOkIIxlGo0fBYmEQ8aVmiSK4OaSpDNCZQ5HMsOUVY6KpGzkm	2026-01-28 01:37:26	2026-01-28 01:51:39
\.


--
-- Name: bancos_numero_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bancos_numero_seq', 4, true);


--
-- Name: cheques_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cheques_id_seq', 1, false);


--
-- Name: cuentas_codigo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cuentas_codigo_seq', 5, true);


--
-- Name: facturas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.facturas_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 11, true);


--
-- Name: proveedores_codigo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.proveedores_codigo_seq', 133, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 2, true);


--
-- PostgreSQL database dump complete
--

\unrestrict Pr8hT9tw7xIBG2knu2pLyTtdbCbaOMjTcq925L5ayp0UCXaRn3LJy5z8gnDqP52

