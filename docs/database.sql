create database wdpai
    with owner avnadmin;

create table public.role
(
    id_role   serial
        primary key,
    role_name varchar(50) not null
);

alter table public.role
    owner to avnadmin;

create table public.user_details
(
    id      serial
        primary key,
    name    varchar(100) not null,
    surname varchar(100) not null
);

alter table public.user_details
    owner to avnadmin;

create table public."user"
(
    id              serial
        primary key,
    id_user_details integer      not null
        constraint fk_user_userdetails
            references public.user_details
            on delete cascade,
    email           varchar(255) not null,
    password        varchar(255) not null
);

alter table public."user"
    owner to avnadmin;

create table public.user_role
(
    id_user integer not null
        constraint fk_userrole_user
            references public."user"
            on delete cascade,
    id_role integer not null
        constraint fk_userrole_role
            references public.role
            on delete cascade,
    primary key (id_user, id_role)
);

alter table public.user_role
    owner to avnadmin;

create table public.types
(
    id_type   serial
        primary key,
    type_name varchar(100) not null
);

alter table public.types
    owner to avnadmin;

create table public.expenses
(
    id      serial
        primary key,
    id_user integer        not null
        constraint fk_expenses_user
            references public."user"
            on delete cascade,
    id_type integer        not null
        constraint fk_expenses_type
            references public.types
            on delete restrict,
    date    date           not null,
    name    varchar(255)   not null,
    price   numeric(10, 2) not null
);

alter table public.expenses
    owner to avnadmin;

create view public.view_user_expenses(user_id, first_name, last_name, expense_name, price, date, type_name) as
SELECT u.id       AS user_id,
       ud.name    AS first_name,
       ud.surname AS last_name,
       e.name     AS expense_name,
       e.price,
       e.date,
       t.type_name
FROM expenses e
         JOIN "user" u ON e.id_user = u.id
         JOIN user_details ud ON u.id_user_details = ud.id
         JOIN types t ON e.id_type = t.id_type;

alter table public.view_user_expenses
    owner to avnadmin;

create view public.view_user_roles(user_id, first_name, last_name, role_name) as
SELECT u.id       AS user_id,
       ud.name    AS first_name,
       ud.surname AS last_name,
       r.role_name
FROM "user" u
         JOIN user_details ud ON u.id_user_details = ud.id
         JOIN user_role ur ON ur.id_user = u.id
         JOIN role r ON ur.id_role = r.id_role;

alter table public.view_user_roles
    owner to avnadmin;

create function public.get_user_total_expenses(p_user_id integer) returns numeric
    language plpgsql
as
$$
DECLARE
    total NUMERIC(10,2);
BEGIN
    SELECT COALESCE(SUM(price), 0)
    INTO total
    FROM expenses
    WHERE id_user = p_user_id;

    RETURN total;
END;
$$;

alter function public.get_user_total_expenses(integer) owner to avnadmin;

create function public.check_expense_price() returns trigger
    language plpgsql
as
$$
BEGIN
    IF NEW.price < 0 THEN
        RAISE EXCEPTION 'Price cannot be negative!';
    END IF;
    RETURN NEW;
END;
$$;

alter function public.check_expense_price() owner to avnadmin;

create trigger trig_check_expense_price
    before insert or update
    on public.expenses
    for each row
execute procedure public.check_expense_price();

INSERT INTO role (role_name) VALUES
                                 ('admin'),
                                 ('client');


INSERT INTO user_details (name, surname) VALUES
                                             ('Admin', 'Kowalski'),
                                             ('Client', 'Nowak');


INSERT INTO "user" (id_user_details, email, password) VALUES
                                                          (1, 'admin@example.com', 'admin123'),
                                                          (2, 'client@example.com', 'client123');


INSERT INTO user_role (id_user, id_role) VALUES
                                             (1, 1),  -- Kowalski -> admin
                                             (2, 2);  -- Nowak -> client


INSERT INTO types (type_name) VALUES
                                  ('Groceries'),
                                  ('House'),
                                  ('Health'),
                                  ('Going Out'),
                                  ('Utilities');

INSERT INTO expenses (id_user, id_type, date, name, price) VALUES
(2, 1, '2025-01-03', 'Grocery store', 40.00),
(2, 4, '2025-01-04', 'Dinner out', 80.00),
(2, 3, '2025-01-05', 'Pharmacy meds', 18.75);