<?php

/**
 * @file
 * Process theme data.
 *
 * Use this file to run your theme specific implimentations of theme functions,
 * such preprocess, process, alters, and theme function overrides.
 *
 * Preprocess and process functions are used to modify or create variables for
 * templates and theme functions. They are a common theming tool in Drupal, often
 * used as an alternative to directly editing or adding code to templates. Its
 * worth spending some time to learn more about these functions - they are a
 * powerful way to easily modify the output of any template variable.
 *
 * Preprocess and Process Functions SEE: http://drupal.org/node/254940#variables-processor
 * 1. Rename each function and instance of "adaptivetheme_subtheme" to match
 *    your subthemes name, e.g. if your theme name is "footheme" then the function
 *    name will be "footheme_preprocess_hook". Tip - you can search/replace
 *    on "adaptivetheme_subtheme".
 * 2. Uncomment the required function to use.
 */


/**
 * Preprocess variables for the html template.
 */
/*
function dunsel_7_preprocess_html(&$vars) {
  global $theme_key;
  // Two examples of adding custom classes to the body.

  // Add a body class for the active theme name.
  // $vars['classes_array'][] = drupal_html_class($theme_key);

  // Browser/platform sniff - adds body classes such as ipad, webkit, chrome etc.
  // $vars['classes_array'][] = css_browser_selector();

}
// */


/**
 * Process variables for the html template.
 */
/* -- Delete this line if you want to use this function
function adaptivetheme_subtheme_process_html(&$vars) {
}
// */

/**
 * Override or insert variables for the page templates.
*/

function acumen_2018_preprocess_page(&$variables, $hook) {
    if (isset($variables['node'])) {
        $variables['theme_hook_suggestions'][]='page__'.$variables['node']->type;
    }
}

function clean_adaptive_theme_2017_process_page(&$vars) {
   

}


/**
 * Override or insert variables into the node templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_node(&$vars) {
}
function adaptivetheme_subtheme_process_node(&$vars) {
}
// */


/**
 * Override or insert variables into the comment templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_comment(&$vars) {
}
function adaptivetheme_subtheme_process_comment(&$vars) {
}
// */


/**
 * Override or insert variables into the block templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_block(&$vars) {
}
function adaptivetheme_subtheme_process_block(&$vars) {
}
// */

function get_dept_title($tid) {
    $loaded_term = entity_load('taxonomy_term',array($tid));
    return $loaded_term[$tid]->name;
}

function display_features_prev_next($node){
    
  //$src_path='http://'.explode('/',path_to_theme())[1].'/'.variable_get('file_public_path').'/';
  $src_path='http://acumen.cas.lehigh.edu/sites/acumen.cas.lehigh.edu/files/';

    //get the language
    $lang = $node->language;
    //get the issue id
    $issue = $node->field_issue[$lang][0]['target_id'];
    $feature_index = $node->field_feature_order[$lang][0]['value'];

    $prev_next = array();

    $prev_exists = TRUE;
    //if the index is 0 or 7 there will be no next
    if($feature_index == 0){
        $prev_exists = FALSE;
    }
    else{
        //$previous = $feature_index - 1;
        $prev_next[] = $feature_index - 1;
    }
    $next_exists = TRUE;
    if($feature_index == 6){
        $next_exists = FALSE;
    }
    else{
        //$next = $feature_index + 1;
        $prev_next[] = $feature_index + 1;
    }
    
    $feature_query = new EntityFieldQuery();
    $feature_query->entityCondition('entity_type','node')
                  ->entityCondition('bundle','feature')
                  ->fieldCondition('field_issue','target_id',$issue,'=')
                  ->fieldCondition('field_feature_order','value',$prev_next,'IN');
    $query_results = $feature_query->execute();

    if(!empty($query_results['node'])){
        $prediv = '<div id="previous-feature">';
        $nexdiv = '<div id="next-feature">';
        
        $query_keys = array_keys($query_results['node']);
        $loaded_ents = entity_load('node',$query_keys);
        $return_html = '';
        if(count($loaded_ents)>2){
            $nodelist = '';
            foreach($loaded_ents as $ent){
                $nodelist .= '<a href="/node/'.$ent->nid.'">node '.$ent->nid.'</a>,';
            }
            return '<div><h1 style="color:red">Duplicate feature order please check the following nodes:</h1><p>'.trim($nodelist,',').'</div>';
        }
        elseif(count($loaded_ents) == 1){
            //only 1 other feature, determine if it is an error, which might occur while building each issue, or correct
            $other_feat = $loaded_ents[$query_keys[0]];
            $feat_order = $other_feat->field_feature_order[$lang][0]['value'];
            $feat_nid = $other_feat->nid;
            $feat_title = $other_feat->title;
            
            $feat_author = $other_feat->field_author[$lang][0]['value'];
            $feat_blurb = $other_feat->field_tag_line[$lang][0]['value'];
            $feat_thumb = $other_feat->field_cover_image[$lang][0]['filename'];
            

            $feature_card = '<a class="feature-card-wrapper" href="/node/'.$feat_nid.'"><div class="internal-feature-card">'.
                '<div class="feature-card-image"><img class="feature-thumb" src="'.$src_path.$feat_thumb.'" width="100%"></div>'.
                '<div class="feature-card-text">'.
                '<div class="next-text">Next<span class="next-arrow">&rsaquo;</span></div><div class ="feature-card-title">'.$feat_title.'</div>'.
                '</div></div></a>';

            $feature_card_prev = '<a class="feature-card-wrapper" href="/node/'.$feat_nid.'"><div class="internal-feature-card">'.
                '<div class="feature-card-image"><img class="feature-thumb" src="'.$src_path.$feat_thumb.'" width="100%"></div>'.
                '<div class="feature-card-text">'.
                '<div class="prev-text"><span class="prev-arrow">&lsaquo;</span>Previous</div><div class ="feature-card-title">'.$feat_title.'</div>'.
                '</div></div></a>';

            
            if(!$next_exists){
                return $prediv.$feature_card_prev.'</div>';
            }
            elseif(!$prev_exists){
                return $nexdiv.$feature_card.'</div>';
            }
            elseif($feat_order < $feature_index){
                return $prediv.$feature_card_prev.'</div>'.$nexdiv.'NEXT MISSING</div>';
            }
            else{
                return $prediv.'Previous MiSSING</div>'.$nexdiv.$feature_card.'</div>';
            }
        }
        else{
            //this means there are only 2, first after gettin this feature orders lets make sure that they aren't the same.
            $feat_0 = $loaded_ents[$query_keys[0]];
            $feat_0_order = $feat_0->field_feature_order[$lang][0]['value'];
            $feat_0_nid = $feat_0->nid;
            $feat_0_title = $feat_0->title;
            //these should be filled in because they are required, but before pushing this out I should put in a check to make sure they are
            $feat_0_author = $feat_0->field_author[$lang][0]['value'];
            $feat_0_blurb = $feat_0->field_tag_line[$lang][0]['value'];
            $feat_0_thumb = $feat_0->field_cover_image[$lang][0]['filename'];

            $feature_0_card = '<a class="feature-card-wrapper" href="/node/'.$feat_0_nid.'"><div class="internal-feature-card">'.
                '<div class="feature-card-image"><img class="feature-thumb" src="'.$src_path.$feat_0_thumb.'" width="100%"></div>'.
                '<div class="feature-card-text">'.
                '<div class="prev-text"><span class="prev-arrow">&lsaquo;</span>Previous</div><div class ="feature-card-title">'.$feat_0_title.'</div>'.
                '</div></div></a>';
            

            $feat_1 = $loaded_ents[$query_keys[1]];
            $feat_1_order = $feat_1->field_feature_order[$lang][0]['value'];
            $feat_1_nid = $feat_1->nid;
            $feat_1_title = $feat_1->title;
            //these should be filled in because they are required, but before pushing this out I should put in a check to make sure they are
            $feat_1_author = $feat_1->field_author[$lang][0]['value'];
            $feat_1_blurb = $feat_1->field_tag_line[$lang][0]['value'];
            $feat_1_thumb = $feat_1->field_cover_image[$lang][0]['filename'];

            $feature_1_card = '<a class="feature-card-wrapper" href="/node/'.$feat_1_nid.'"><div class="internal-feature-card">'.
                '<div class="feature-card-text">'.
                '<div class="next-text">Next <span class="next-arrow">&rsaquo;</span></div><div class ="feature-card-title">'.$feat_1_title.'</div></div>'.
                '<div class="feature-card-image"><img class="feature-thumb" src="'.$src_path.$feat_1_thumb.'" width="100%"></div></div></a>';
            

            if($feat_0_order == $feat_1_order){
                //uh-oh these shouldn't be the same!
                $following_nodes = '<a href="/node/'.$feat_0_nid.'">node '.$feat_0_nid.'</a>,<a href="/node/'.$feat_1_nid.'">node '.$feat_1_nid.'</a>';
                return '<div><h1 style="color:red">Duplicate feature order please check the following nodes:</h1><p>'.$following_nodes.'</div>';
            }
            elseif($feat_0_order > $feat_1_order){
                return $prediv.$feature_1_card.'</div>'.$nexdiv.$feature_0_card.'</div>';
            }
            else{
                return $prediv.$feature_0_card.'</div>'.$nexdiv.$feature_1_card.'</div>';
            }
        }
    }
    else{
        return '<div><h1>Both PREVIOUS and NEXT MISSING</h1></div>';
    }
}