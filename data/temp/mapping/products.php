<?php
return array(
    'fields' => array(
        'vendor_id' => 0,
        'article' => NULL,
    ),
    'store' => [
        'article',
        'price',
        'file_is_3d_model',
    ],
    'fieldMeta' => array(
        'article' => array(
            'filter' => 'text',
            'type' => 'text',
            'aggs' => true
        ),
        'vendor' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),

        'vendor_name' => array(
            'filter' => 'terms',
            'type' => 'text',
            'aggs' => true
        ),


        'collection' => array(
            'filter' => 'terms',
            'type' => 'text',
            'aggs' => false
        ),


        'price' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'diff_price' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'remain_count' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => false
        ),
        'voltage' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'colors' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'materials' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'forms' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'ip_class' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),

        // range
        'ploshad_osvesheniya' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'diametr_vrezki' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'diametr_vrezkis' => array(
            'filter' => 'facets',
            'field_rating' => 'diametr_vrezki',
            'type' => 'long',
            'aggs' => true
        ),
        'height' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'diameter' => array(
            'filter' => 'range',
            'type' => 'float',
            'aggs' => true
        ),
        'dlina_vrezki' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'length' => array(
            'filter' => 'range',
            'type' => 'float',
            'aggs' => true
        ),
        'shirina_vrezki' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'width' => array(
            'filter' => 'range',
            'type' => 'integer',
            'aggs' => true
        ),
        'power' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'num_of_socket' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),


        // boolean
        'category' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => false
        ),
        'popular_selling' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => false
        ),
        'sub_category' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => false
        ),

        'parent' => array(
            'filter' => 'terms',
            'type' => 'text',
            'aggs' => false,
            'fielddata' => true
        ),


        'availability' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => true
        ),
        'sale' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),
        'new' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),
        'mega_sale' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),
        'defective' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),
        'published' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),
        'separate_on' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),


        // Options
        'marker' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ), // Маркеры это объединение sale new defective availability
        'lamp_style' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'popular_count' => array(
            'filter' => 'terms',
            'type' => 'integer',
            'aggs' => true
        ),
        'interer' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'krepej' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'tags' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => false
        ),
        'mesto_montaza' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'osobennost' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'lamp_type' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'ottenok' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'viklyuchatel' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'sub_lin_razm' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'sub_oc_razm' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'mesto_prim' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'country_orig' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'lamp_socket' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'pu_dimmer' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'forma' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'forma_plafona' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'armature_material' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'plafond_material' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'dopolnitelno' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'shop_availability' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'armature_color' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'plafond_color' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),


        'tsvet_temp' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'light_flow' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),
        'light_temperatures' => array(
            'filter' => 'range',
            'type' => 'long',
            'aggs' => true
        ),

        'diametr_plafona_sm' => array(
            'filter' => 'range',
            'type' => 'float',
            'aggs' => true
        ),
        'vysota_plafona_abazhura_sm' => array(
            'filter' => 'range',
            'type' => 'float',
            'aggs' => true
        ),

        'shade_direction' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'diffuser' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'tip_upravleniya' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'vid_vyklyuchatelya' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'tip_poverhnosti_plafonov_new' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        'tip_podklyucheniya_new' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),

        'napravlennyy_svet' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => true
        ),


        // Для подбора аналогов
        'plafon_share' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),

        // Для подбора аналогов
        'candle_lamp_vision' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),
        // Для подбора аналогов
        'hrustal' => array(
            'filter' => 'terms',
            'type' => 'keyword',
            'aggs' => true
        ),

        'file_is_3d_model' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),

        'file_is_manual' => array(
            'filter' => 'term',
            'type' => 'boolean',
            'aggs' => false
        ),

    )
);
