<?php

namespace App\Http\Resources;

use App\Facades\Api;
use App\Facades\CampaignLocalization;
use App\Facades\Img;
use App\Facades\Mentions;
use App\Models\Item;
use App\Models\MiscModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class EntityResource extends JsonResource
{
    use ApiSync;

    /** @var bool If the entity should come with related objects */
    public bool $withRelated = false;

    /** @var bool If the entity comes with the misc model */
    public bool $withMisc = false;

    /**
     * Get related objects for this entity
     * @return $this
     */
    public function withRelated(): self
    {
        $this->withRelated = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withMisc(): self
    {
        $this->withMisc = true;
        return $this;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Entity $entity */
        $entity = $this->resource;

        $url = $entity->url();
        $lang = request()->header('kanka-locale', auth()->user()->locale ?? 'en');
        $url = Str::replaceFirst('campaign/', $lang . '/campaign/', $url);
        $apiViewUrl = 'campaigns.' . $entity->pluralType() . '.show';

        // On the API subdomain? Fix urls
        if (Api::isSubdomain()) {
            $url = Api::fixUrl($url);
        }

        $data = [
            'id' => $entity->id,
            'name' => $entity->name,
            // @phpstan-ignore-next-line
            'type' => $entity->type(),
            'type_id' => $entity->type_id,
            'child_id' => $entity->entity_id,
            'tags' => $entity->tags->pluck('id')->toArray(),
            'is_private' => (bool) $entity->is_private,
            'is_template' => (bool) $entity->is_template,
            'campaign_id' => $entity->campaign_id,
            'is_attributes_private' => (bool) $entity->is_attributes_private,
            'tooltip' => $entity->tooltip,
            'header_image' => $entity->header_image,
            'image_uuid' => $entity->image_uuid,

            'created_at' => $entity->created_at,
            'created_by' => $entity->created_by,
            'updated_at' => $entity->updated_at,
            'updated_by' => $entity->updated_by,


            'urls' => [
                'view' => $url,
                'api' => Route::has($apiViewUrl) ? route($apiViewUrl, [$entity->campaign_id, $entity->entity_id]) : null,
            ]
        ];

        if (request()->get('related', false)) {
            $data['attributes'] = AttributeResource::collection($entity->attributes);
            $data['entity_notes'] = PostResource::collection($entity->posts);
            $data['posts'] = $data['entity_notes'];
            $data['entity_events'] = EntityEventResource::collection($entity->events);
            //$data['entity_files'] = EntityFileResource::collection($this->files);
            $data['relations'] = RelationResource::collection($entity->relationships);
            $data['inventory'] = InventoryResource::collection($entity->inventories);
            $data['entity_abilities'] = EntityAbilityResource::collection($entity->abilities);
            //$data['entity_links'] = EntityLinkResource::collection($entity->links);
        }

        if (request()->get('related', false) || request()->get('image', false)) {
            if (empty($entity->child)) {
                $data['child'] = 'Invalid child, please contact Jay on Discord with the following: EntityResource for #' . $entity->id;
            } else {
                $campaign = CampaignLocalization::getCampaign();
                $image = $campaign->superboosted() && !empty($entity->image);
                $data['child'] = [
                    'image' => $image ? $entity->image->path : $entity->child->image,
                    'image_full' => $image ? Img::resetCrop()->url($entity->image->path) : $entity->fullsize()->avatarV2(),
                    'image_thumb' => $image ? Img::crop(40, 40)->url($entity->image->path) : $entity->avatarSize(40)->avatarV2(),
                    'has_custom_image' => $image || !empty($entity->child->image),
                ];

                /*if (request()->get('entry')) {
                    $data['child']['entry'] = $entity->child->hasEntry() ? $entity->child->entry : null;
                    $data['child']['entry_parsed'] = $entity->child->hasEntry() ? Mentions::map($entity->child->entry) : null;
                }*/
            }
        }

        // Get the actual model
        if ($this->withMisc) {
            // @phpstan-ignore-next-line
            $className = 'App\Http\Resources\\' . ucfirst($entity->type()) . 'Resource';
            if (class_exists($className)) {
                $obj = new $className($entity->child);
                $data['child'] = $obj;
            } else {
                $data['child'] = 'unknown child class ' . $className;
            }
        }

        return $data;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  array $prepared
     * @return array|string
     */
    public function entity(array $prepared = [])
    {
        /** @var mixed|MiscModel|Item $misc */
        $misc = $this->resource;
        if (!$misc->entity) {
            return 'permission issue';
        }

        $galleryImage = $misc->entity->image;
        $campaign = CampaignLocalization::getCampaign();
        $superboosted = $campaign->superboosted();
        $boosted = $campaign->boosted();

        $url = $misc->getLink();
        $lang = request()->header('kanka-locale', auth()->user()->locale ?? 'en');
        $url = Str::replaceFirst('campaign/', $lang . '/campaign/', $url);
        $apiViewUrl = 'campaigns.' . $misc->entity->pluralType() . '.show';

        // On the API subdomain? Fix urls
        if (Api::isSubdomain()) {
            $url = Api::fixUrl($url);
        }

        $merged = [
            'id' => $misc->id,
            'name' => $misc->name,
            'entry' => $misc->hasEntry() ? $misc->entry : null,
            'entry_parsed' => $misc->hasEntry() ? Mentions::map($misc) : null,
            'tooltip' => $boosted ? ($misc->entity->tooltip ?: null) : null,
            'image' => $misc->image,
            'focus_x' => $misc->entity->focus_x,
            'focus_y' => $misc->entity->focus_y,

            // Image
            // @phpstan-ignore-next-line
            'image_full' => !empty($misc->image) ? $misc->thumbnail(0) : $misc->entity->image?->getImagePath(0),
            'image_thumb' => $misc->thumbnail(),
            'has_custom_image' => !empty($misc->image) || !empty($galleryImage),
            'image_uuid' => $superboosted && $misc->entity->image ? $misc->entity->image->id : null,

            // Header
            'header_full' => $misc->entity->getHeaderUrl($superboosted),
            'header_uuid' => $superboosted && $misc->entity->header ? $misc->entity->header->id : null,
            'has_custom_header' => $misc->entity->hasHeaderImage($superboosted),

            'is_private' => (bool) $misc->is_private,
            'is_template' => (bool) $misc->entity->is_template,

            'is_attributes_private' => (bool) $misc->entity->is_attributes_private,

            'entity_id' => $misc->entity->id,
            'tags' => $misc->entity->tags()->pluck('tags.id')->toArray(),

            'created_at' => $misc->created_at,
            'created_by' => $misc->entity->created_by,
            'updated_at' => $misc->updated_at,
            'updated_by' => $misc->entity->updated_by,

            'urls' => [
                'view' => $url,
                'api' => Route::has($apiViewUrl) ? route($apiViewUrl, [$misc->campaign_id, $misc->id]) : null,
            ]
        ];

        // Foreign elements
        $attributes = $misc->getAttributes();
        if (array_key_exists('location_id', $attributes)) {
            $merged['location_id'] = $misc->location_id;
        }
        if (array_key_exists('character_id', $attributes)) {
            $merged['character_id'] = $misc->character_id;
        }

        if (request()->get('related', false) || $this->withRelated) {
            $merged['attributes'] = AttributeResource::collection($misc->entity->attributes);
            $merged['posts'] = PostResource::collection($misc->entity->posts);
            $merged['entity_notes'] = $merged['posts'];
            $merged['entity_events'] = EntityEventResource::collection($misc->entity->events);
            $merged['relations'] = RelationResource::collection($misc->entity->relationships);
            $merged['inventory'] = InventoryResource::collection($misc->entity->inventories);
            $merged['entity_abilities'] = EntityAbilityResource::collection($misc->entity->abilities);
            $merged['entity_assets'] = EntityAssetResource::collection($misc->entity->assets);
        }

        $final = array_merge($merged, $prepared);
        //ksort($final);
        return $final;
    }
}
