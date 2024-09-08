<?php

    namespace App\Http\Controllers;

    use App\Models\Brand;
    use App\Models\Category;
    use App\Models\Coupon;
    use App\Models\Order;
    use App\Models\OrderItem;
    use App\Models\Product;
    use App\Models\Slide;
    use App\Models\Transaction;
    use Illuminate\Http\Request;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Str;
    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;

    class AdminController extends Controller
    {
        public function index()
        {
            $orders = Order::orderBy("created_at", "DESC")->take(10);
            $dashboardDatas = DB::select("select sum(total) as TotalAmount,
                                                sum(if(status = 'ordered', total, 0)) as TotalOrderedAmount,
                                                sum(if(status = 'delivered', total, 0)) as TotalDeliveredAmount,
                                                sum(if(status = 'caceled', total, 0)) as TotalCanceledAmount,
                                                count(*) as Total,
                                                sum(if(status = 'ordered',1, 0)) as TotalOrdered,
                                                sum(if(status = 'delivered', 1, 0)) as TotalDelivered,
                                                sum(if(status = 'caceled', 1, 0)) as TotalCanceled
                                                from orders");
            return view('admin.index', compact(["orders", "dashboardDatas"]));
        }

        /* brand */
        public function brand()
        {
            $brands = Brand::query()->orderBy('id', 'desc')->paginate(10);
            return view('admin.brand', compact('brands'));
        }

        public function brand_add()
        {
            return view('admin.brand-add');
        }

        public function brand_store(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:brands,slug', 'image' => 'mimes:png,jpg,jpeg|max:2048']);

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateBrandThumdnailImage($image, $file_name);
            $brand->image = $file_name;
            $brand->save();
            return redirect()->route('admin.brand')->with('status', 'Brand has been added succesfully');
        }

        public function GenerateBrandThumdnailImage($image, $imageName)
        {
            $destinationPath = public_path('uploads/brand');
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());
            $img->cover(124, 124, 'top');
            $img->resize(124, 124)->save($destinationPath . '/' . $imageName);
        }

        public function brand_edit($id)
        {
            $brand = Brand::find($id);
            return view('admin.brand-edit', compact('brand'));
        }

        public function brand_update(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:brands,slug,' . $request->id, 'image' => 'mimes:png,jpg,jpeg|max:2048']);

            $brand = Brand::find($request->id);
            $brand->name = $request->name;
            $brand->slug = Str::slug($request->name);
            if ($request->hasFile('image')) {
                if (File::exists(public_path('uploads/brand') . '/' . $brand->image)) {
                    File::delete(public_path('uploads/brand') . '/' . $brand->image);
                }
                $image = $request->file('image');
                $file_extension = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateBrandThumdnailImage($image, $file_name);
                $brand->image = $file_name;
            }
            $brand->save();
            return redirect()->route('admin.brand')->with('status', 'Brand has been edited succesfully');
        }

        public function brand_delete($id)
        {
            $brand = Brand::find($id);
            if (File::exists(public_path('uploads/brand') . '/' . $brand->image)) {
                File::delete(public_path('uploads/brand') . '/' . $brand->image);
            }
            $brand->delete();
            return redirect()->route('admin.brand')->with('status', 'Brand has been deleted succesfully');
        }

        /* category */
        public function category()
        {
            $categories = Category::query()->orderBy('id', 'desc')->paginate(10);
            return view('admin.category', compact('categories'));
        }

        public function category_add()
        {
            return view('admin.category-add');
        }

        public function category_store(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:categories,slug', 'image' => 'mimes:png,jpg,jpeg|max:2048']);

            $category = new Category();
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateCategoryThumdnailImage($image, $file_name);
            $category->image = $file_name;
            $category->save();
            return redirect()->route('admin.category')->with('status', 'Category has been added succesfully');
        }

        public function GenerateCategoryThumdnailImage($image, $imageName)
        {
            $destinationPath = public_path('uploads/category');
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());
            $img->cover(124, 124, 'top');
            $img->resize(124, 124)->save($destinationPath . '/' . $imageName);
        }

        public function category_edit($id)
        {
            $category = Category::find($id);
            return view('admin.category-edit', compact('category'));
        }

        public function category_update(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:categories,slug,' . $request->id, 'image' => 'mimes:png,jpg,jpeg|max:2048']);

            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            if ($request->hasFile('image')) {
                if (File::exists(public_path('uploads/category') . '/' . $category->image)) {
                    File::delete(public_path('uploads/category') . '/' . $category->image);
                }
                $image = $request->file('image');
                $file_extension = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateCategoryThumdnailImage($image, $file_name);
                $category->image = $file_name;
            }
            $category->save();
            return redirect()->route('admin.category')->with('status', 'Category has been edited succesfully');
        }

        public function category_delete($id)
        {
            $category = Category::find($id);
            if (File::exists(public_path('uploads/category') . '/' . $category->image)) {
                File::delete(public_path('uploads/category') . '/' . $category->image);
            }
            $category->delete();
            return redirect()->route('admin.category')->with('status', 'Category has been deleted succesfully');
        }

        /* product */
        public function product()
        {
            $products = Product::query()->orderBy('id', 'desc')->paginate(10);
            return view('admin.product', compact('products'));
        }

        public function product_add()
        {
            $brands = Brand::all();
            $categories = Category::all();
            return view('admin.product-add', compact(["brands", "categories"]));
        }

        public function product_store(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:products,slug', 'image' => 'mimes:png,jpg,jpeg|max:2048', "short_description" => "required", "description" => "required", "regular_price" => "required", "sale_price" => "required", "SKU" => "required", "stock_status" => "required", "featured" => "required", "quantity" => "required", "category_id" => "required", "brand_id" => "required",]);

            $product = new Product();
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sale_price = $request->sale_price;
            $product->SKU = $request->SKU;
            $product->stock_status = $request->stock_status;
            $product->featured = $request->featured;
            $product->quantity = $request->quantity;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $current_timestamp = Carbon::now()->timestamp;

            if ($request->hasFile("image")) {
                $image = $request->file("image");
                $imageName = $current_timestamp . "." . $image->extension();
                $this->GenerateProductThumdnailImage($image, $imageName);
                $product->image = $imageName;
            }

            $gallery_arr = array();
            $gallery_images = "";
            $counter = 1;
            if ($request->hasFile("images")) {
                $allowFileExtension = ["jpg", "jpeg", "png"];
                $files = $request->file("images");
                foreach ($files as $file) {
                    $gextension = $file->getClientOriginalExtension();
                    $gcheck = in_array($gextension, $allowFileExtension);
                    if ($gcheck) {
                        $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                        $this->GenerateProductThumdnailImage($file, $gfileName);
                        array_push($gallery_arr, $gfileName);
                        $counter += 1;
                    }
                }
                $gallery_images = implode(",", $gallery_arr);
            }
            $product->images = $gallery_images;

            $product->save();
            return redirect()->route('admin.product')->with('status', 'Product has been added succesfully');
        }

        public function GenerateProductThumdnailImage($image, $imageName)
        {
            $destinationPathThumbnail = public_path('uploads/product/thumbnails');
            $destinationPath = public_path('uploads/product');

            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());

            //            $img->cover(540, 690, 'top');
            $img->save($destinationPath . '/' . $imageName);

            $img->save($destinationPathThumbnail . '/' . $imageName);
        }

        public function product_edit($id)
        {
            $brands = Brand::all();
            $categories = Category::all();
            $product = Product::find($id);
            return view('admin.product-edit', compact(['product', 'brands', 'categories']));
        }

        public function product_update(Request $request)
        {
            $request->validate(['name' => 'required', 'slug' => 'required|unique:products,slug,' . $request->id, 'image' => 'mimes:png,jpg,jpeg|max:2048', "short_description" => "required", "description" => "required", "regular_price" => "required", "sale_price" => "required", "SKU" => "required", "stock_status" => "required", "featured" => "required", "quantity" => "required", "category_id" => "required", "brand_id" => "required",]);

            $product = Product::find($request->id);
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->short_description = $request->short_description;
            $product->description = $request->description;
            $product->regular_price = $request->regular_price;
            $product->sale_price = $request->sale_price;
            $product->SKU = $request->SKU;
            $product->stock_status = $request->stock_status;
            $product->featured = $request->featured;
            $product->quantity = $request->quantity;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $current_timestamp = Carbon::now()->timestamp;

            if ($request->hasFile("image")) {
                if (File::exists(public_path('uploads/product') . '/' . $product->image)) {
                    File::delete(public_path('uploads/product') . '/' . $product->image);
                }
                if (File::exists(public_path('uploads/product/thumbnails') . '/' . $product->image)) {
                    File::delete(public_path('uploads/product.thumbnails') . '/' . $product->image);
                }
                $image = $request->file("image");
                $imageName = $current_timestamp . "." . $image->extension();
                $this->GenerateProductThumdnailImage($image, $imageName);
                $product->image = $imageName;
            }

            $gallery_arr = array();
            $gallery_images = "";
            $counter = 1;
            if ($request->hasFile("images")) {
                foreach (explode(",", $product->images) as $ofile) {
                    if (File::exists(public_path('uploads/product') . '/' . $ofile)) {
                        File::delete(public_path('uploads/product') . '/' . $ofile);
                    }
                    if (File::exists(public_path('uploads/product/thumbnails') . '/' . $ofile)) {
                        File::delete(public_path('uploads/product.thumbnails') . '/' . $ofile);
                    }
                }

                $allowFileExtension = ["jpg", "jpeg", "png"];
                $files = $request->file("images");
                foreach ($files as $file) {
                    $gextension = $file->getClientOriginalExtension();
                    $gcheck = in_array($gextension, $allowFileExtension);
                    if ($gcheck) {
                        $gfileName = $current_timestamp . "-" . $counter . "." . $gextension;
                        $this->GenerateProductThumdnailImage($file, $gfileName);
                        array_push($gallery_arr, $gfileName);
                        $counter += 1;
                    }
                }
                $gallery_images = implode(",", $gallery_arr);
            }
            $product->images = $gallery_images;

            $product->save();
            return redirect()->route('admin.product')->with('status', 'Product has been edited succesfully');
        }

        public function product_delete($id)
        {
            $product = Product::find($id);
            if (File::exists(public_path('uploads/product/thumbnails') . '/' . $product->image)) {
                File::delete(public_path('uploads/product/thumbnails') . '/' . $product->image);
            }
            if ($product->images) {
                foreach (explode(",", $product->images) as $img) {
                    if (File::exists(public_path('uploads/product') . '/' . $img)) {
                        File::delete(public_path('uploads/product') . '/' . $img);
                    }
                }
            }
            $product->delete();
            return redirect()->route('admin.product')->with('status', 'Product has been deleted succesfully');
        }

        /* coupon */
        public function coupons()
        {
            $coupons = Coupon::orderBy("expiry_date", "DESC")->paginate(12);
            return view("admin.coupon", compact(["coupons"]));
        }

        public function coupon_add()
        {
            return view("admin.coupon-add");
        }

        public function coupon_store(Request $request)
        {
            $request->validate(['type' => 'nullable', 'code' => 'required|unique:coupons,code', 'value' => 'required', 'cart_value' => 'required', 'expiry_date' => 'required']);

            $coupon = new Coupon();
            $coupon->code = $request->code;
            $coupon->type = $request->type;
            $coupon->value = $request->value;
            $coupon->cart_value = $request->cart_value;
            $coupon->expiry_date = $request->expiry_date;

            $coupon->save();
            return redirect()->route('admin.coupon')->with('status', 'Coupon has been added succesfully');
        }

        public function coupon_edit($id)
        {
            $coupon = Coupon::find($id);
            return view('admin.coupon-edit', compact('coupon'));
        }

        public function coupon_update(Request $request)
        {
            $request->validate(['type' => 'nullable', 'code' => 'required|unique:coupons,code,' . $request->id, 'value' => 'required', 'cart_value' => 'required', 'expiry_date' => 'required']);

            $coupon = Coupon::find($request->id);
            $coupon->code = $request->code;
            $coupon->type = $request->type;
            $coupon->value = $request->value;
            $coupon->cart_value = $request->cart_value;
            $coupon->expiry_date = $request->expiry_date;

            $coupon->save();
            return redirect()->route('admin.coupon')->with('status', 'Coupon has been edited succesfully');
        }

        public function coupon_delete($id)
        {
            $coupon = Coupon::find($id);
            $coupon->delete();
            return redirect()->route('admin.coupon')->with('status', 'Coupon has been deleted succesfully');
        }

        /* orders */
        public function orders()
        {
            $orders = Order::orderBy('created_at', 'DESC')->paginate(12);
            return view("admin.orders", compact('orders'));
        }

        public function order_items($order_id)
        {
            $order = Order::find($order_id);
            $orderitems = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(12);
            $transaction = Transaction::where('order_id', $order_id)->first();
            return view("admin.order-details", compact('order', 'orderitems', 'transaction'));
        }

        public function update_order_status(Request $request)
        {
            $order = Order::find($request->order_id);
            $order->status = $request->order_status;
            if ($request->order_status == 'delivered') {
                $order->delivered_date = Carbon::now();
            } else if ($request->order_status == 'canceled') {
                $order->canceled_date = Carbon::now();
            }
            $order->save();
            if ($request->order_status == 'delivered') {
                $transaction = Transaction::where('order_id', $request->order_id)->first();
                $transaction->status = "approved";
                $transaction->save();
            }
            return back()->with("status", "Status changed successfully!");
        }

        public function slides()
        {
            $slides = Slide::orderBy("id", "DESC")->paginate(12);
            return view("admin.slides", compact(["slides"]));
        }

        public function slide_add()
        {
            return view('admin.slide-add');
        }

        public function slide_store(Request $request)
        {
            $request->validate(['tagline' => 'required', 'title' => 'required', 'image' => 'mimes:png,jpg,jpeg|max:2048', 'subtitle' => 'required', 'link' => 'required']);

            $slide = new Slide();
            $slide->tagline = $request->tagline;
            $slide->title = $request->title;
            $slide->subtitle = $request->subtitle;
            $slide->link = $request->link;
            $slide->status = $request->status;

            $image = $request->file('image');
            $file_extension = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateSlideThumdnailImage($image, $file_name);
            $slide->image = $file_name;
            $slide->save();
            return redirect()->route('admin.slides')->with('status', 'Slide has been added succesfully');
        }

        public function GenerateSlideThumdnailImage($image, $imageName)
        {
            $destinationPath = public_path('uploads/slide');
            $manager = new ImageManager(new Driver());
            $img = $manager->read($image->path());
            $img->cover(500, 500, 'top');
            $img->save($destinationPath . '/' . $imageName);
        }

        public function slide_edit($id)
        {
            $slide = Slide::find($id);
            return view('admin.slide-edit', compact('slide'));
        }

        public function slide_update(Request $request)
        {
            $request->validate(['tagline' => 'required', 'title' => 'required', 'image' => 'mimes:png,jpg,jpeg|max:2048', 'subtitle' => 'required', 'link' => 'required']);

            $slide = Slide::find($request->id);
            $slide->tagline = $request->tagline;
            $slide->title = $request->title;
            $slide->subtitle = $request->subtitle;
            $slide->link = $request->link;
            $slide->status = $request->status;

            if ($request->hasFile('image')) {
                if (File::exists(public_path('uploads/slide') . '/' . $slide->image)) {
                    File::delete(public_path('uploads/slide') . '/' . $slide->image);
                }
                $image = $request->file('image');
                $file_extension = $request->file('image')->extension();
                $file_name = Carbon::now()->timestamp . '.' . $file_extension;
                $this->GenerateSlideThumdnailImage($image, $file_name);
                $slide->image = $file_name;
            }
            $slide->save();
            return redirect()->route('admin.slides')->with('status', 'Slide has been edited succesfully');
        }

        public function slide_delete($id)
        {
            $slide = Slide::find($id);
            if (File::exists(public_path('uploads/slide') . '/' . $slide->image)) {
                File::delete(public_path('uploads/slide') . '/' . $slide->image);
            }
            $slide->delete();
            return redirect()->route('admin.slides')->with('status', 'Slide has been deleted succesfully');
        }
    }

