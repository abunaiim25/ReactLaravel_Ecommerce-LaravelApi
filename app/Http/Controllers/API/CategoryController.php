<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; //add

class CategoryController extends Controller
{
    //=================Store Category========================
    public function store(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'meta_title' => 'required|max:191',
        ]);

        //post fail or post Database
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = new Categories;
            $category->slug = $request->input('slug');
            $category->name = $request->input('name');
            $category->description = $request->input('description');
            $category->status = $request->input('status') == true ? '1' : '0';
            $category->meta_title = $request->input('meta_title');
            $category->meta_keywords = $request->input('meta_keywords');
            $category->meta_description = $request->input('meta_description');
            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Category Added Successfully'
            ]);
        }
    }


    //=================View Category========================
    public function view()
    {
        $category = Categories::all();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }

    //================= Edit page by Id ========================
    public function edit($id)
    {
        $category = Categories::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([ //not found
                'status' => 404,
                'message' => "No Category Id Found",
            ]);
        }
    }

    //================= Update Edit page by Id ========================
    public function update(Request $request, $id)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'meta_title' => 'required|max:191',
        ]);

        //post fail or post Database
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = Categories::find($id);
            if ($category) {
                $category->slug = $request->input('slug');
                $category->name = $request->input('name');
                $category->description = $request->input('description');
                $category->status = $request->input('status') == true ? '1' : '0';
                $category->meta_title = $request->input('meta_title');
                $category->meta_keywords = $request->input('meta_keywords');
                $category->meta_description = $request->input('meta_description');
                $category->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Category Updated Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'No category Id Found'
                ]);
            }
        }
    }

    
        //================= destroy page by Id ========================
        public function destroy($id)
        {
            $category = Categories::find($id);
            if ($category) {

                $category->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Category Deleted Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    'status' => 404,
                    'message' => 'No category Id Found'
                ]);
            }
        }
}
