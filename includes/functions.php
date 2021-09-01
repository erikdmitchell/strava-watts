<?php
    
function stwatt_add_athlete($data = '') {
    if (empty($data))
        return;
        
    if (stwatt_athlete_exists($data->id))
        return;
        
/*
stdClass Object
        (

            [username] => erikdmitchell
            [resource_state] => 2
            [bio] => 
            [city] => Phoenixville
            [state] => PA
            [country] => United States
            [premium] => 1
            [summit] => 1
            [created_at] => 2010-03-21T01:12:51Z
            [updated_at] => 2021-08-31T17:04:00Z
            [badge_type_id] => 1
            [weight] => 90.7185
            [profile_medium] => https://dgalywyr863hv.cloudfront.net/pictures/athletes/4334/84512/1/medium.jpg
            [profile] => https://dgalywyr863hv.cloudfront.net/pictures/athletes/4334/84512/1/large.jpg
            [friend] => 
            [follower] => 
        )    
*/ 


            

    $insert_data = array(
        'age' => '',
        'athlete_id' => $data->id,
        'first_name' => $data->firstname,
        'gender' => $data->sex,
        'last_name' => $data->lastname,
    );
    
    stwatt()->athletes_db->insert($insert_data, 'athlete');   
}

function stwatt_athlete_exists($athlete_id=0) {
    if (stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id ))
        return true;
        
    return false;
}

function stwatt_is_athlete_authorized($athlete_id=0) {
    if (stwatt()->athletes_db->get_column_by( 'id', 'athlete_id', $athlete_id ))
        return true;
        
    return false;
}