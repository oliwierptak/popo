
    /**
     * @return <<GET_METHOD_RETURN_DOCKBLOCK>>
     */
    public function <<GET_METHOD_NAME>>()<<GET_METHOD_RETURN_TYPE>>
    {
        return $this->popoGetValue('<<PROPERTY_NAME>>');
    }

    /**
     * @param <<SET_METHOD_PARAM_DOCKBLOCK>>
     *
     * @return <<SET_METHOD_RETURN_DOCKBLOCK>>
     */
    public function <<SET_METHOD_NAME>>(<<SET_METHOD_PARAMETERS>>)<<SET_METHOD_RETURN_TYPE>>
    {
        $this->popoSetValue('<<PROPERTY_NAME>>', $<<PROPERTY_NAME>>);

        return $this;
    }

    /**
     * @throws \UnexpectedValueException
     *
     * @return <<REQUIRE_METHOD_RETURN_DOCKBLOCK>>
     */
    public function <<REQUIRE_METHOD_NAME>>()<<REQUIRE_METHOD_RETURN_TYPE>>
    {
        $this->assertPropertyValue('<<PROPERTY_NAME>>');

        return <<REQUIRE_METHOD_RETURN_TYPE_CAST>>$this->popoGetValue('<<PROPERTY_NAME>>');
    }
