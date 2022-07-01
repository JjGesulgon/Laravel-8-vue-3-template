<?php
/*
|--------------------------------------------------------------------------
| Auto filtering relationships for laravel models
|--------------------------------------------------------------------------
|
| Here is where all filtering of relationships logic happens.
| Current version supports single or array value of filtering one column
| from a relationship.
|
*/

namespace App\Traits;

trait FilterRelationships
{
    /**
     * Data array that will filter with relationship
     *
     * @var array
     */
    private static $data = [];

    /**
     * Filter relationships.
     *
     * @param  \Illuminate\Database\Eloquent\Model &$query
     * @param  \Illuminate\Http\Request            &$request
     * @return void
     */
    public function filterRelationships(&$query, &$request)
    {
        foreach ($request->all() as $column => $value) {
            if (strpos($column, 'FromModel') ||
                strpos($column, 'Model') ||
                strpos($column, 'from_') ||
                strpos($column, 'from_model_')
            ) {
                if (method_exists($this, self::convertToRelationship($column))) {
                    if ($value !== null && $value !== '') {
                        self::$data[$column] = $value;
                    }
                }
            }
        }

        if (isset(self::$data)) {
            foreach (self::$data as $column => $value) {
                if ($this->checkIfQueryShouldBeStrict($request->is_strict)) {
                    $query->whereHas(self::convertToRelationship($column), function ($query) use ($request, $column, $value) {
                        $query->where(function ($query) use ($column, $value) {
                            if (! is_array($value)) {
                                $query->where($this->convertToColumn($column), 'LIKE', '%' . $value . '%');
                            } else {
                                foreach ($value as $arrayValue) {
                                    $query->orWhere($this->convertToColumn($column), 'LIKE', '%' . $arrayValue . '%');
                                }
                            }
                        });
                    });
                } else {
                    $query->orWhereHas(self::convertToRelationship($column), function ($query) use ($request, $column, $value) {
                        $query->where(function ($query) use ($column, $value) {
                            if (! is_array($value)) {
                                $query->where($this->convertToColumn($column), 'LIKE', '%' . $value . '%');
                            } else {
                                foreach ($value as $arrayValue) {
                                    $query->orWhere($this->convertToColumn($column), 'LIKE', '%' . $arrayValue . '%');
                                }
                            }
                        });
                    });
                }
            }
        }
    }

    /**
     * Convert key string to model relationship.
     *
     * @param  string $column
     * @return string
     */
    public function convertToRelationship($column)
    {
        $relationship = strtolower(
            preg_replace(
                '/.+?(?:FromModel)|.+?(?:Model)|.+?(?:_from_)|.+?(?:_from_model_)/',
                '',
                $column
            )
        );

        if (strrpos($relationship, '_') !== false) {
            preg_match_all('/\_./', $relationship, $matches);

            $matches = $matches[0];

            foreach ($matches as $match) {
                $replacement = str_replace('_', '', strtoupper($match));

                $relationship = str_replace($match, $replacement, $relationship);
            }
        }

        return $relationship;
    }
}
