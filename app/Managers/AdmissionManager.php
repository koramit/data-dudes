<?php

namespace App\Managers;

use App\Models\Admission;
use App\Models\AttendingStaff;
use App\Models\Ward;

class AdmissionManager
{
    public function manage(array $data)
    {
        $admission = Admission::whereAn($data['an'])->first();

        $ward = $this->maintainWard($data['ward_name'], $data['ward_name_short']);
        $staff = $this->maintainAttendingStaff($data['attending_name'], $data['attending_license_no'] ?? $data['attending_name']);

        if ($admission) {
            if ($admission->ward_id != $ward->id) {
                $admission->admitTransfers()->create([
                    'ward_id' => $ward->id,
                    'attending_staff_id' => $staff->id,
                ]);
            }

            $admission->ward_id = $ward->id;
            $admission->attending_staff_id = $staff->id;
            $admission->encountered_at = $data['encountered_at'];
            $admission->dismissed_at = $data['dismissed_at'];
            $admission->discharge_type_name = $data['discharge_type_name'];
            $admission->discharge_status_name = $data['discharge_status_name'];
            $admission->checked_at = now();
            $admission->save();

            return;
        }

        // create
        $admission = new Admission;
        $admission->hn = $data['hn'];
        $admission->an = $data['an'];
        $admission->name = $data['patient_name'];
        $admission->dob = $data['dob'];
        $admission->gender = $data['gender'] === 'female' ? 1 : 2;
        $admission->encountered_at = $data['encountered_at'];
        $admission->dismissed_at = $data['dismissed_at'];
        $admission->discharge_type_name = $data['discharge_type_name'];
        $admission->discharge_status_name = $data['discharge_status_name'];

        $ward = $this->maintainWard($data['ward_name'], $data['ward_name_short']);
        $staff = $this->maintainAttendingStaff($data['attending_name'], $data['attending_license_no'] ?? $data['attending_name']);

        $admission->ward_id = $ward->id;
        $admission->attending_staff_id = $staff->id;
        $admission->checked_at = now();
        $admission->save();

        $admission->admitTransfers()->create([
            'ward_id' => $ward->id,
            'attending_staff_id' => $staff->id,
        ]);
    }

    protected function maintainWard($name, $nameShort)
    {
        if ($ward = Ward::whereName($name)->first()) {
            return $ward;
        }

        return Ward::create([
            'name' => $name,
            'name_short' => $nameShort,
        ]);
    }

    protected function maintainAttendingStaff($name, $licenseNo)
    {
        if ($staff = AttendingStaff::whereLicenseNo($licenseNo)->first()) {
            return $staff;
        }

        return AttendingStaff::create([
            'name' => $name,
            'license_no' => $licenseNo,
        ]);
    }
}
