UPDATE `labdb`.`reagent` 
SET 
    `lab_id` = '{{lab_id}}',
    `name` = '{{name}}',
    `formula` = '{{formula}}',
    `CAS` = '{{cas}}',
    `location` = '{{location}}',
    `private` = '{{private}}',
    `secure` = '{{secure}}'
WHERE
    (`id` = '{{id}}');
