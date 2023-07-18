# Locations

---

- [All Locations](#all-locations)

- [Single Location](#location)
- [Create a Location](#create-location)
- [Update a Location](#update-location)
- [Delete a Location](#delete-location)

<a name="all-locations"></a>
## All Locations

You can get a list of all the locations of a campaign by using the following endpoint.

> {warning} All endpoints documented here are hosted on `api.kanka.io/{{version}}/campaigns/{campaign.id}/`.


| Method | URI | Headers |
| :- |   :-   |  :-  |
| GET/HEAD | `locations` | Default |

### URL Parameters

The list of returned locations can be filtered. The available filters are available here: <a href="/en/helpers/api-filters?type=location" target="_blank">API filters</a>.

### Results
```json
{
    "data": [
        {
            "id": 1,
            "name": "Mordor",
            "entry": "\n<p>Lorem Ipsum.</p>\n",
            "image": "{path}",
            "image_full": "{url}",
            "image_thumb": "{url}",
            "has_custom_image": false,
            "is_private": true,
            "location_id": null,
            "entity_id": 5,
            "tags": [],
            "created_at":  "2019-01-30T00:01:44.000000Z",
            "created_by": 1,
            "updated_at":  "2019-08-29T13:48:54.000000Z",
            "updated_by": 1,
            "parent_location_id": 4,
            "type": "Kingdom"
        }
    ]
}
```


<a name="location"></a>
## Location

To get the details of a single location, use the following endpoint.

| Method | URI | Headers |
| :- |   :-   |  :-  |
| GET/HEAD | `locations/{location.id}` | Default |

### Results
```json
{
    "data": {
        "id": 1,
        "name": "Mordor",
        "entry": "\n<p>Lorem Ipsum.</p>\n",
        "image": "{path}",
        "image_full": "{url}",
        "image_thumb": "{url}",
        "has_custom_image": false,
        "is_private": true,
        "location_id": null,
        "entity_id": 5,
        "tags": [],
        "created_at":  "2019-01-30T00:01:44.000000Z",
        "created_by": 1,
        "updated_at":  "2019-08-29T13:48:54.000000Z",
        "updated_by": 1,
        "parent_location_id": 4,
        "type": "Kingdom"
    }

}
```


<a name="create-location"></a>
## Create a Location

To create a location, use the following endpoint.

| Method | URI | Headers |
| :- |   :-   |  :-  |
| POST | `locations` | Default |

### Body

| Parameter | Type | Detail |
| :- |   :-   |  :-  |
| `name` | `string` (Required) | Name of the location |
| `entry` | `string` | The html description of the location |
| `type` | `string` | Type of location |
| `parent_location_id` | `integer` | The parent location id (where this location is located)|
| `tags` | `array` | Array of tag ids |
| `is_private` | `boolean` | If the location is only visible to `admin` members of the campaign |
| `image_url` | `string` | URL to a picture to be used for the location |
| `entity_image_uuid` | `string` | Gallery image UUID for the entity image (limited to superboosted campaigns) |
| `entity_header_uuid` | `string` | Gallery image UUID for the entity header (limited to superboosted campaigns) |


### Results

> {success} Code 200 with JSON body of the new location.


<a name="update-location"></a>
## Update a Location

To update a location, use the following endpoint.

| Method | URI | Headers |
| :- |   :-   |  :-  |
| PUT/PATCH | `locations/{location.id}` | Default |

### Body

The same body parameters are available as for when creating a location.

### Results

> {success} Code 200 with JSON body of the updated location.


<a name="delete-location"></a>
## Delete a Location

To delete a location, use the following endpoint.

| Method | URI | Headers |
| :- |   :-   |  :-  |
| DELETE | `locations/{location.id}` | Default |

### Results

> {success} Code 200 with JSON.
