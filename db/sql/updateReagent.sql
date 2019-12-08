UPDATE labdb0_0_4.reagent 
SET 
    lab_id = '{{lab_id}}',
    name_common = '{{name_common}}',
    formula = '{{formula}}',
    CAS = '{{CAS}}',
    locations = '{{locations}}',
    private = '{{private}}',
    secure = '{{secure}}'
WHERE
    id = '{{id}}';