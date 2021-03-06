<?php

class GroupFieldTestCase extends WP_UnitTestCase {

	function testAddField() {

		$group  = new AS_Group_Field( 'group', 'Group Title', array() );
		$field1 = new AS_Text_Field( 'foo', 'Title', array( 1 ) );
		$field2 = new AS_Text_Field( 'bar', 'Title', array( 2, 3 ), array( 'repeatable' => true ) );

		$group->add_field( $field1 );
		$group->add_field( $field2 );
	
		$this->assertArrayHasKey( 'foo', $group->get_fields() );
		$this->assertArrayHasKey( 'bar', $group->get_fields() );
	
	}

	function testGetValues() {
		
		$group  = new AS_Group_Field( 'group', 'Group Title', array() );
		$field1 = new AS_Text_Field( 'foo', 'Title', array() );
		$field2 = new AS_Text_Field( 'bar', 'Title', array() );
	
		$group->add_field( $field1 );
		$group->add_field( $field2 );
		
		$group->values = $values = array( 
			'group' => array( 
				'foo' => array( 1, 2 ),
				'bar' => array( 3, 4 ) 
			)
		);

		$this->assertEquals( $group->get_values(), $values );
	
	}

	function testParseSaveValues() {

		$group  = new AS_Group_Field( 'group', 'Group Title', array() );
		$field1 = new AS_Text_Field( 'foo', 'Title', array( 1 ) );
		$field2 = new AS_Text_Field( 'bar', 'Title', array( 2, 3 ), array( 'repeatable' => true ) );
	
		$group->add_field( $field1 );
		$group->add_field( $field2 );
		
		$group->set_values( array( 
			'group' => array( 
				'foo' => array( 1 ),
				'bar' => array( 2, 3 ) 
			),
		) );

		$expected = array( 
			'group' => array( 
				'foo' => 1,
				'bar' => array( 2, 3 ) 
			)
		);

		$group->parse_save_values();

		$this->assertEquals( $group->get_values(), $expected );

	}

	function testFieldNameAttrValue() {

		$group  = new AS_Group_Field( 'group', 'Group Title', array() );
		$field1 = new AS_Text_Field( 'foo', 'Title', array( 1, 2 ) );
	
		$group->add_field( $field1 );

		// Standard use of ID attribute
		$id_attr = $field1->get_the_name_attr();
		$this->assertEquals( $id_attr, 'group[as-group-0][foo][as-field-0]' );

		// Using append
		$id_attr = $field1->get_the_name_attr( '[bar]' );
		$this->assertEquals( $id_attr, 'group[as-group-0][foo][as-field-0][bar]' );

		// Test repeatable group.
		$group->field_index = 1;
		$id_attr = $field1->get_the_name_attr();
		$this->assertEquals( $id_attr, 'group[as-group-1][foo][as-field-0]' );
		$group->field_index = 0; // Unset

		// Test repeatable field within group.
		$field1->field_index = 1;
		$id_attr = $field1->get_the_name_attr();
		$this->assertEquals( $id_attr, 'group[as-group-0][foo][as-field-1]' );
		$field1->field_index = 0; // Unset

	}

	function testFieldIdAttrValue() {

		$group  = new AS_Group_Field( 'group', 'Group Title', array() );
		$field1 = new AS_Text_Field( 'foo', 'Title', array( 1, 2 ) );
	
		$group->add_field( $field1 );

		// Standard use of ID attribute
		$id_attr = $field1->get_the_id_attr();
		$this->assertEquals( $id_attr, 'group-as-group-0-foo-as-field-0' );

		// Using append
		$id_attr = $field1->get_the_id_attr( 'bar' );
		$this->assertEquals( $id_attr, 'group-as-group-0-foo-as-field-0-bar' );

		// Test repeatable group.
		$group->field_index = 1;
		$id_attr = $field1->get_the_id_attr();
		$this->assertEquals( $id_attr, 'group-as-group-1-foo-as-field-0' );
		$group->field_index = 0; // Unset

		// Test repeatable field within group.
		$field1->field_index = 1;
		$id_attr = $field1->get_the_id_attr();
		$this->assertEquals( $id_attr, 'group-as-group-0-foo-as-field-1' );
		$field1->field_index = 0; // Unset

	}

}