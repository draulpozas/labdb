CREATE VIEW `user_view` AS SELECT * FROM reagent, laboratory WHERE secure = 0;
CREATE VIEW `private_view` AS SELECT id, lab_id, name_common, formula, CAS, private, secure FROM reagent, laboratory;