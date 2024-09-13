-- Database generated with pgModeler (PostgreSQL Database Modeler).
-- pgModeler  version: 0.9.2
-- PostgreSQL version: 12.0
-- Project Site: pgmodeler.io
-- Model Author: ---


-- Database creation must be done outside a multicommand file.
-- These commands were put in this file only as a convenience.
-- -- object: flowers_db | type: DATABASE --
-- -- DROP DATABASE IF EXISTS flowers_db;
-- CREATE DATABASE flowers_db;
-- -- ddl-end --
-- 

-- DROP TABLE IF EXISTS public.flowers CASCADE;
CREATE TABLE public.materials (
    material_id serial NOT NULL,
    name text,
    material_type text,
    price decimal,
    CONSTRAINT materials_pk PRIMARY KEY (material_id)
);

-- Table name changed from 'public.stock' to 'public.inventory'
CREATE TABLE public.inventory (
    inventory_id serial NOT NULL,
    stock_quantity smallint,
    material_id integer,
    CONSTRAINT inventory_pk PRIMARY KEY (inventory_id)
);

-- Foreign key constraint name changed from 'flowers' to 'materials' in 'public.inventory'
ALTER TABLE public.inventory DROP CONSTRAINT IF EXISTS flowers CASCADE;
ALTER TABLE public.inventory ADD CONSTRAINT materials FOREIGN KEY (material_id)
REFERENCES public.materials (material_id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE;

-- Table name changed from 'public.orders' to 'public.construction_orders'
CREATE TABLE public.construction_orders (
    order_id serial NOT NULL,
    customer_name text,
    order_date date,
    quantity smallint,
    material_id integer,
    CONSTRAINT construction_orders_pk PRIMARY KEY (order_id)
);

-- Foreign key constraint name changed from 'flowers' to 'materials' in 'public.construction_orders'
ALTER TABLE public.construction_orders DROP CONSTRAINT IF EXISTS flowers CASCADE;
ALTER TABLE public.construction_orders ADD CONSTRAINT materials FOREIGN KEY (material_id)
REFERENCES public.materials (material_id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE;


