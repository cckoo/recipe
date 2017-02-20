<?php

namespace App\Http\Controllers\Api;

use App\Recipe;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    public function index(Request $request) {
        $page = $request->get('page', 1);
        $pageSize = 20;
        $recipeModels = Recipe::skip(($page-1) * $pageSize)->take($pageSize)->get();
        $recipes = [];

        foreach ($recipeModels as $recipe) {
            $recipes[] = [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'image' => $recipe->thumb,
                'reading' => $recipe->reading,
            ];
        }

        $this->displayJson($recipes);
    }

    public function show($id) {
        $recipeModel = Recipe::with('hasManyMaterials')->with('hasManySteps')->find($id);
        $recipe = [];
        $recipe['id'] = $recipeModel->id;
        $recipe['name'] = $recipeModel->name;
        $recipe['image'] = $recipeModel->images;
        $recipe['reading'] = $recipeModel->reading;
        $recipe['tip'] = $recipeModel->tips;
        $recipe['description'] = $recipeModel->description;
        foreach ($recipeModel->hasManyMaterials as $materialModel) {
            $material['name'] = $materialModel->name;
            $material['unit'] = $materialModel->unit;
            $recipe['materials'][] = $material;
        }

        foreach ($recipeModel->hasManySteps as $stepModel) {
            $step['sort'] = $stepModel->sorts;
            $step['content'] = $stepModel->contents;
            $step['image'] = $stepModel->images;
            $recipe['steps'][] = $step;
        }

        $this->displayJson($recipe);
    }

    private function displayJson($data) {
        header('Content-Type:text/html;charset=utf-8');
        header('Content-type: application/json');
        $res['code'] = 0;
        $res['data'] = $data;

        echo json_encode($res);
    }
}
