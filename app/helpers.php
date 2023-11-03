<?php

function flat_ancestors($model) {
        $result = [];
        if ($model->parent) {
          $result[] = $model->parent;
          $result = array_merge($result, flat_ancestors($model->parent));
        }
        return $result;
      }
      
    function flat_descendants($model) {
        $result = [];
        foreach ($model->children as $child) {
          $result[] = $child;
          if ($child->children) {
            $result = array_merge($result, flat_descendants($child));
          }
        }
        return $result;
      }
?>