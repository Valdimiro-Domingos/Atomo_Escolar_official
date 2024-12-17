<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'designation' => $this->designation,
            'nif' => $this->nif,
            'logo' => $this->logo,
            'foundation_date' => $this->foundation_date,
          'share_capital' => $this->share_capital,
            'contact' => $this->contact,
            'description' => $this->description,
          'representative_name' => $this->representative_name,
         'representative_identification' => $this->representative_identification,
         'general_manager' => $this->general_manager,
         'pedagogical_manager' => $this->pedagogical_manager,
         'provincial_manager' => $this->provincial_manager,
         'municipal_manager' => $this->municipal_manager,
           'country' => $this->country,
           'city' => $this->city,
           'address' => $this->address,
           'email' => $this->email,
           'whatsapp' => $this->whatsapp,
           'facebook' => $this->facebook,
           'web_site' => $this->web_site,

        ];
    }
}
