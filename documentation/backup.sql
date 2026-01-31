--
-- PostgreSQL database dump
--

\restrict xG1nBgOjLdT313mW0DI9au3txdFljZcyH28wP2kKQ1Em9SjmfRgpeMvdz3Tr6oj

-- Dumped from database version 18.1 (Debian 18.1-1.pgdg13+2)
-- Dumped by pg_dump version 18.1 (Debian 18.1-1.pgdg13+2)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: items; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.items (
    id integer NOT NULL,
    user_id integer,
    title text NOT NULL,
    description text,
    price numeric(10,2) NOT NULL,
    phone_number text,
    photo_path text DEFAULT '/uploads/default_photo.png'::text,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.items OWNER TO docker;

--
-- Name: items_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.items_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.items_id_seq OWNER TO docker;

--
-- Name: items_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.items_id_seq OWNED BY public.items.id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    description text
);


ALTER TABLE public.roles OWNER TO docker;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO docker;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users (
    id integer NOT NULL,
    firstname character varying(100) NOT NULL,
    lastname character varying(100) NOT NULL,
    email character varying(150) NOT NULL,
    password character varying(255) NOT NULL,
    bio text,
    enabled boolean DEFAULT true
);


ALTER TABLE public.users OWNER TO docker;

--
-- Name: users_roles; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users_roles (
    user_id integer NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.users_roles OWNER TO docker;

--
-- Name: user_roles_view; Type: VIEW; Schema: public; Owner: docker
--

CREATE VIEW public.user_roles_view AS
 SELECT u.email,
    r.name
   FROM ((public.users u
     JOIN public.users_roles ur ON ((u.id = ur.user_id)))
     JOIN public.roles r ON ((r.id = ur.role_id)));


ALTER VIEW public.user_roles_view OWNER TO docker;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO docker;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: items id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.items ALTER COLUMN id SET DEFAULT nextval('public.items_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: items; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.items (id, user_id, title, description, price, phone_number, photo_path, created_at) FROM stdin;
4	3	Plecak (super cena)	Curabitur ut bibendum quam. Maecenas sed elit lectus. Fusce vel condimentum augue, vel maximus felis. In suscipit, augue a ultrices tincidunt, ante augue gravida diam, hendrerit lacinia velit eros vel turpis. Vivamus placerat, nibh pretium luctus rhoncus, libero dui facilisis libero, nec blandit turpis tellus in quam.	50.99	+48 123 458 690	/uploads/1769781224_1766275692_backpack.png	2026-01-30 13:53:44.589887+00
5	3	Super kurtka skórzana	Sed rhoncus sem vitae augue iaculis aliquam. Suspendisse sollicitudin egestas aliquet. Fusce faucibus porttitor magna faucibus molestie. Maecenas mollis, odio sit amet pulvinar interdum, purus enim fermentum elit, a consectetur dui mi non augue. Sed id ligula nunc. Nam ut commodo lacus.	499.99	+48 123 456 789	/uploads/1769781264_1766276301_jacket.png	2026-01-30 13:54:24.083746+00
7	3	Rower w ekstra cenie	Nam porttitor mauris sapien, nec iaculis lectus condimentum at. Ut nec lorem nec metus posuere placerat. Curabitur convallis ante neque, a malesuada urna tempor vel. Maecenas luctus convallis nibh, nec convallis dui vestibulum nec. Nam accumsan dui vitae enim pellentesque congue. Aliquam ac nisl sit amet elit ultricies efficitur. Donec quam urna, laoreet et nunc sed, vulputate mollis nunc. Curabitur ac tellus diam.	47.99	+48 733 825 568	/uploads/1769825319_bicycle.png	2026-01-31 02:08:39.139522+00
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.roles (id, name, description) FROM stdin;
1	USER	Użytkownik
2	ADMIN	Administrator
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.users (id, firstname, lastname, email, password, bio, enabled) FROM stdin;
3	admin	admin	admin@admin.com	$2y$10$I6LHaplTNsGcpvxgUB4I8uVdEKS7ML1W3N9q5SwN8e5CgmJ9m/gMS		t
4	test	test	test@gmail.com	$2y$10$bZCQp6FaKVvDjxAto57DgOQ8kXPcKtrYk0eDjvbZmpO42.Oijo1pK		t
7	Jan	Kowalski	januslukasz.webdeveloper@gmail.com	$2y$10$khnIYfJKq9QCXyNDfdtSqe8S7jEDTYNaDHgIJUVvZSWP14dQnEj7W		t
\.


--
-- Data for Name: users_roles; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.users_roles (user_id, role_id) FROM stdin;
3	2
4	2
7	1
\.


--
-- Name: items_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.items_id_seq', 9, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.roles_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.users_id_seq', 7, true);


--
-- Name: items items_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.items
    ADD CONSTRAINT items_pkey PRIMARY KEY (id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users_roles users_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users_roles
    ADD CONSTRAINT users_roles_pkey PRIMARY KEY (user_id, role_id);


--
-- Name: items items_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.items
    ADD CONSTRAINT items_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users_roles users_roles_role_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users_roles
    ADD CONSTRAINT users_roles_role_id_fkey FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: users_roles users_roles_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users_roles
    ADD CONSTRAINT users_roles_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict xG1nBgOjLdT313mW0DI9au3txdFljZcyH28wP2kKQ1Em9SjmfRgpeMvdz3Tr6oj

