<?php
namespace App\Http\Controllers;

use App\Models\VisaMaster;
use Illuminate\Http\Request;
use Image;
class VisaMasterController extends Controller
{
    public function index(Request $request)
{
    $query = VisaMaster::query();

    if ($request->has('name') && !empty($request->name)) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->has('visa_type') && !empty($request->visa_type)) {
        $query->where('visa_type', 'like', '%' . $request->visa_type . '%');
    }

    if ($request->has('stay_validity') && !empty($request->stay_validity)) {
        $query->where('stay_validity', $request->stay_validity);
    }

    if ($request->has('visa_validity') && !empty($request->visa_validity)) {
        $query->where('visa_validity', $request->visa_validity);
    }

    if ($request->has('status') && !empty($request->status)) {
        $query->where('status', $request->status);
    }

    $records = $query->paginate(10)->appends($request->query());

    return view('visa_masters.index', compact('records'));
}


    public function create()
    {
        return view('visa_masters.create');
    }

    public function store(Request $request)
{
    $options['allow_img_size'] = 10;
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:' . ($options['allow_img_size'] * 1024),
        'visa_type' => 'required|string',
        'insurance_mandate' => 'nullable|string',
        'insurance_information' => 'nullable|string',
        'stay_validity' => 'nullable|string',
        'visa_validity' => 'nullable|string',
        'adult_fees' => 'nullable|numeric',
        'child_fees' => 'nullable|numeric',
        'express_charges' => 'nullable|numeric',
        'super_express_charges' => 'nullable|numeric',
        'description' => 'nullable|string',
        'documents_checklist' => 'nullable|string',
    ]);

    $visaMaster = new VisaMaster();
    $visaMaster->name = $request->name;
    $visaMaster->visa_type = $request->visa_type;
    $visaMaster->insurance_mandate = $request->insurance_mandate;
    $visaMaster->insurance_information = $request->insurance_information;
    $visaMaster->stay_validity = $request->stay_validity;
    $visaMaster->visa_validity = $request->visa_validity;
    $visaMaster->adult_fees = $request->adult_fees;
    $visaMaster->child_fees = $request->child_fees;
    $visaMaster->express_charges = $request->express_charges;
    $visaMaster->super_express_charges = $request->super_express_charges;
    $visaMaster->description = $request->description;
    $visaMaster->documents_checklist = $request->documents_checklist;
    $visaMaster->normal_processing_timeline = $request->normal_processing_timeline;
    $visaMaster->express_processing_timeline = $request->express_processing_timeline;
    $visaMaster->super_express_processing_timeline = $request->super_express_processing_timeline;

    $destinationPath = public_path('/uploads/visa/');
    $thumbPath = public_path('/uploads/visa/thumb/');
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }
    
    if (!file_exists($thumbPath)) {
        mkdir($thumbPath, 0777, true);
    }
    
    if ($request->hasFile('image')) {

        $fileName = $request->image->getClientOriginalName();
        $file = request()->file('image');
        $fileNameArr = explode('.', $fileName);
        $fileNameExt = end($fileNameArr);
        $newName = date('His').rand() . time() . '.' . $fileNameExt;
        
        $file->move($destinationPath, $newName);
        
        $img = Image::make($destinationPath.$newName);
                    
        $img->resize(100, 100, function($constraint) {
            $constraint->aspectRatio();
        });
        
        $img->save(public_path($thumbPath.$newName));
        $visaMaster->image = $newName;
    }

    $visaMaster->save();

    return redirect()->route('visa-masters.index')->with('success', 'Visa Master saved successfully.');
}

    

    public function show(VisaMaster $visaMaster)
    {
        return view('visa_masters.show', compact('visaMaster'));
    }

    public function edit($id)
{
    $visaMaster = VisaMaster::findOrFail($id); // Fetch the Visa Master record
    return view('visa_masters.edit', compact('visaMaster')); // Pass the data to the view
}


public function update(Request $request, $id)
{
    $visaMaster = VisaMaster::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'visa_type' => 'required',
    ]);

    

    $visaMaster->name = $request->name;
    $visaMaster->visa_type = $request->visa_type;
    $visaMaster->insurance_mandate = $request->insurance_mandate;
    $visaMaster->insurance_information = $request->insurance_information;
    $visaMaster->stay_validity = $request->stay_validity;
    $visaMaster->visa_validity = $request->visa_validity;
    $visaMaster->adult_fees = $request->adult_fees;
    $visaMaster->child_fees = $request->child_fees;
    $visaMaster->express_charges = $request->express_charges;
    $visaMaster->super_express_charges = $request->super_express_charges;
    $visaMaster->description = $request->description;
    $visaMaster->documents_checklist = $request->documents_checklist;
    $visaMaster->normal_processing_timeline = $request->normal_processing_timeline;
    $visaMaster->express_processing_timeline = $request->express_processing_timeline;
    $visaMaster->super_express_processing_timeline = $request->super_express_processing_timeline;
    $destinationPath = public_path('/uploads/visa/');
    $thumbPath = public_path('/uploads/visa/thumb/');
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }
    
    if (!file_exists($thumbPath)) {
        mkdir($thumbPath, 0777, true);
    }
    
        $input = $request->all();
		if ($request->hasFile('image')) {

           
			$fileName = $input['image']->getClientOriginalName();
			$file = request()->file('image');
			$fileNameArr = explode('.', $fileName);
			$fileNameExt = end($fileNameArr);
			$newName = date('His').rand() . time() . '.' . $fileNameExt;
			
			$file->move($destinationPath, $newName);
			$img = Image::make($destinationPath.$newName);
						
            $img->resize(100, 100, function($constraint) {
				$constraint->aspectRatio();
			});
			
			$img->save($thumbPath.$newName);
			
			if($visaMaster->image != 'no-image.png'){
            //** Below code for unlink old image **//
				$oldImage = $destinationPath.$visaMaster->image;
				$oldImageThumb = $thumbPath.$visaMaster->image;
				if(!empty($record->image) && @getimagesize($oldImage) && file_exists($oldImage)) {
					unlink($oldImage);
					unlink($oldImageThumb);
				}
			}
            $visaMaster->image = $newName;
		}
    $visaMaster->save();

    return redirect()->route('visa-masters.index')->with('success', 'Visa Master updated successfully');
}

    public function destroy(VisaMaster $visaMaster)
    {
        $visaMaster->delete();
        return redirect()->route('visa-masters.index')->with('success', 'Visa Master deleted successfully.');
    }
}

