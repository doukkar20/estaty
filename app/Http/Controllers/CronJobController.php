<?php

namespace App\Http\Controllers;

use App\Http\Helpers\VendorPermissionHelper;
use App\Jobs\SubscriptionExpiredMail;
use App\Jobs\SubscriptionReminderMail;
use App\Models\BasicSettings\Basic;
use App\Models\Membership;
use App\Models\Project\ProjectFloorplanImage;
use App\Models\Project\ProjectGalleryImage;
use App\Models\Property\PropertySliderImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    public function expired()
    {
        try {
            $bs = Basic::first();

            $expired_members = Membership::whereDate('expire_date', Carbon::now()->subDays(1))->get();
            foreach ($expired_members as $key => $expired_member) {
                if (!empty($expired_member->vendor)) {
                    $vendor = $expired_member->vendor;
                    $current_package = VendorPermissionHelper::userPackage($vendor->id);
                    if (is_null($current_package)) {
                        SubscriptionExpiredMail::dispatch($vendor, $bs);
                    }
                }
            }

            $remind_members = Membership::whereDate('expire_date', Carbon::now()->addDays($bs->expiration_reminder))->get();
            foreach ($remind_members as $key => $remind_member) {
                if (!empty($remind_member->vendor)) {
                    $vendor = $remind_member->vendor;

                    $nextPacakgeCount = Membership::where([
                        ['vendor_id', $vendor->id],
                        ['start_date', '>', Carbon::now()->toDateString()]
                    ])->where('status', '<>', 2)->count();

                    if ($nextPacakgeCount == 0) {
                        SubscriptionReminderMail::dispatch($vendor, $bs, $remind_member->expire_date);
                    }
                }
                \Artisan::call("queue:work --stop-when-empty");
            }

            // delete unnecessary images 
            $propertyGalleryImage = PropertySliderImage::where('property_id', null)->get();
            $projectGalleryImage = ProjectGalleryImage::where('project_id', null)->get();
            $projectFloorplanImage = ProjectFloorplanImage::where('project_id', null)->get();
            if ($propertyGalleryImage) {
                $now = Carbon::now();
                foreach ($propertyGalleryImage as $image) {
                    $imagesDateTime = Carbon::parse($image->created_at);

                    if ($imagesDateTime->lt($now->subHour())) {
                        @unlink(public_path('assets/img/property/slider-images/' . $image->image));
                        $image->delete();
                    }
                }
            }

            if ($projectGalleryImage) {
                $now = Carbon::now();
                foreach ($projectGalleryImage as $image) {
                    $imagesDateTime = Carbon::parse($image->created_at);
                    if ($imagesDateTime->lt($now->subHour())) {
                        @unlink(public_path('assets/img/project/gallery-images/' . $image->image));
                        $image->delete();
                    }
                }
            }

            if ($projectFloorplanImage) {
                $now = Carbon::now();
                foreach ($projectFloorplanImage as $image) {
                    $imagesDateTime = Carbon::parse($image->created_at);
                    if ($imagesDateTime->lt($now->subHour())) {
                        @unlink(public_path('assets/img/project/floor-paln-images/' . $image->image));
                        $image->delete();
                    }
                }
            }
        } catch (\Exception $e) {
        }
    }
}
