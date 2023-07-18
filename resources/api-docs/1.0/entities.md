# Entities

---

- [Entities](#entities)
- [Entity Types](#entity-types)
- [Single Entity](#entity)
- [Filtering Entities](#filtering-entities)
- [Related Entities](#related-entities)

<a name="entities"></a>
## Entities

Nearly all models in Kanka are based on the concept of entities. A character is an entity, but because of historical choices, there are two actual models.
A `character` is a singular model and endpoint, and a character has both an `id` and an `entity_id` value. The `id` identifies the character against all other **characters**, while the `entity_id` identifies the character against all other **entities**. This can be confusing at first, but should not be an issue with the help of this documentation.

> {warning} All endpoints documented here are hosted on `api.kanka.io/{{version}}/campaigns/{campaign.id}/`. For example, if an endpoint is listed as `characters`, you should use `https://api.kanka.io/{{version}}/campaigns/123456789/characters`.

Some common entities include:

* [Characters](/api-docs/{{version}}/characters)
* [Locations](/api-docs/{{version}}/locations)

### Common Attributes

Most entities have the following attributes.

| Attribute | Type | Description
| :- | :- | :- |
| `id` | `integer` | The id identifying the object against all other objects of the same type. |
| `name` | `string` | The name representing the object. |
| `type` | `string` | The type of entity as a string. (deprecated) |
| `type_id` | `integer` | The type of entity as an integer. |
| `child_id` | `integer` | The id identifying the entity against all other entities of the same type (ie unique character id). |
| `image` | `string` | The local path to the picture of the object. |
| `image_full` | `string` | The url to the picture of the object. |
| `image_thumb` | `string` | The url to the thumbnail of the object. |
| `image_uuid` | `uuid` | The image gallery uuid of the entity (only available for superboosted campaigns) |
| `is_private` | `boolean` | Determines if the object is only visible by `admin` members of the campaign.<br /> If the user requesting the API isn't a member of the `admin` role, such objects won't be returned. |
| `is_template` | `boolean` | Determines if the object is a template. |
| `is_attributes_private` | `boolean` | Determines if the entity's attributes are only visible to members of the campaign's admin role. |
| `tags` | `array` | An array of tags that the object is related to. |
| `created_at` | `object` | An object representing when the object was created (server time) |
| `created_by` | `integer` | The `users`.`id` who created the object.
| `updated_at` | `object` | An object representing when the object was updated (server time) |
| `updated_by` | `integer` | The `users`.`id` who last updated the object.


<a name="entity-types"></a>
## Entity Types

You can see all entity types and their ID's on the following endpoint: [Entity Types](/api-docs/{{version}}/entity-types)


<a name="entity"></a>
## Single Entity

To get the details of a single entity, use the following endpoint.

| Method | URI | Headers |
| :- |   :-   |  :-  |
| GET/HEAD | `entities/{entity.id}` | Default |

### Results
```json
{
    "data": {
        "id": 95,
        "name": "Redkeep",
        "type": "location",
        "child_id": 95,
        "tags": [],
        "is_private": false,
        "campaign_id": 1,
        "is_attributes_private": false,
        "tooltip": null,
        "header_image": null,
        "image_uuid": null,
        "created_at": "2017-12-07T14:23:57.000000Z",
        "created_by": null,
        "updated_at": "2017-12-07T14:23:57.000000Z",
        "updated_by": null
    }
}
```

The `child_id` property in this case is the location's id. So if you want to get the whole location based on this entity, call `locations/95`.

<a name="filtering-entities"></a>
## Filtering Entities

You can filter the returned entities on the `entities/` endpoint with the following options.

| Parameter | Values | Description |
| :- | :- | :- |
| `types` | `character,family` | Filter the returned entities by the `type` field |
| `name` | `string` | The name of the entity (like %% search)|
| `is_private` | `bool` | Search for private entities with `is_private=true` |
| `is_template` | `bool` | Search for entities that are set as templates |
| `created_by` | `int` | User ID of entities created by that user |
| `updated_by` | `int` | User ID of entities updated by that user |
| `tags` | `array` | Filter on tags. Ex `tags[]=5&tags[]=13` |


For example, call `entities?types=item,quest` to get entities of the Item and Quest type.

<a name="related-entities"></a>
## Related Entities

You can call this endpoint with the `?related` option described below to get the entity's related objects. This parameter works for both the `entities/` endpoints and the individual "child" endpoints (ie `characters/`).

There are several models in Kanka which represent objects attached to `entities`.

* [Attributes](/api-docs/{{version}}/attributes)
* [Entity Events](/api-docs/{{version}}/entity-events)
* [Entity Files](/api-docs/{{version}}/entity-files)
* [Entity Mentions](/api-docs/{{version}}/entity-mentions)
* [Entity Tags](/api-docs/{{version}}/entity-tags)
* [Entity Relations](/api-docs/{{version}}/entity-relations)
* [Entity Inventory](/api-docs/{{version}}/entity-inventory)
* [Entity Abilities](/api-docs/{{version}}/entity-abilities)
* [Entity Links](/api-docs/{{version}}/entity-links)
* [Posts](/api-docs/{{version}}/posts)

With each request to an object (ie. `character`, `location`, etc), you can include the following parameter to get those related objects directly.


| Parameter | Type | Description
| :- | :- | :- |
| `related` | `integer` | Set to `1` if you want the entity's related objects |

### Examples

| Method | URI | Headers |
| :- |   :-   |  :-  |
| GET/HEAD | `characters?related=1` | Default |
| GET/HEAD | `characters/1?related=1` | Default |

### Result


```json
{
    "data": [
        {
            "id": 44,
            "name": "Frejya",
            "entry": "Lorem Ipsum",
            "image": null,
            "image_full": "{url}",
            "image_thumb": "{url}",
            "is_private": false,
            "entity_id": 76,
            "tags": [],
            "created_at":  "2019-01-30T00:01:44.000000Z",
            "created_by": null,
            "updated_at":  "2019-08-29T13:48:54.000000Z",
            "updated_by": null,
            "location_id": 2,
            "attributes": [],
            "posts": [],
            "entity_events": [
                {
                    "created_at":  "2019-01-30T00:01:44.000000Z",
                    "created_by": null,
                    "default_order": null,
                    "entity_id": 76,
                    "id": 22,
                    "is_private": false,
                    "name": null,
                    "type": null,
                    "updated_at":  "2019-08-29T13:48:54.000000Z",
                    "updated_by": null,
                    "value": null
                }
            ],
            "entity_files": [],
            "entity_abilities": [],
            "entity_links": [],
            "relations": [],
            "title": null,
            "age": null,
            "sex": null,
            "races": [],
            "type": null,
            "families": [],
            "is_dead": false,
            "traits": []
        }
    ]
}
```

Notice the new array objects `attributes`, `entity_files`, `entity_events`, `posts`, `entity_abilities` and `relations`.
