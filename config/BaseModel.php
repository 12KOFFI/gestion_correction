<?php
namespace Config;

abstract class BaseModel {
    public function toArray(): array {
        return get_object_vars($this);
    }

    public function formArray(array $data): self {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        return $this;
    }
}

?>