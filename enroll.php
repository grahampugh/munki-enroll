<?php

require_once( 'cfpropertylist-1.1.2/CFPropertyList.php' );

// Default catalog
$catalog = 'standard';

// Get the varibles passed by the enroll script
$identifier1 = $_POST["identifier1"];
$identifier2 = $_POST["identifier2"];
$identifier3 = $_POST["identifier3"];
$identifier4 = $_POST["identifier4"];

$hostname   = $_POST["hostname"];

// Ensure we aren't nesting a manifest within itself
// Note that this will create a default manifest - it will not honour any options from DS
if ( $identifier1 == "client-" . $hostname )
	{
		$identifier1 = "_cg_ru"; $identifier2 = "";
	}

// Check if manifest already exists for this machine
echo "\n\tMUNKI-ENROLLER. Checking for existing manifests.\n\n";

if ( file_exists( '../manifests/client-' . $hostname ) )
    {
        echo "\tComputer manifest client-" . $hostname . " already exists.\n";
        echo "\tThis will be replaced.\n\n";
    }
else
    {
        echo "\tComputer manifest does not exist. Will create.\n\n";
    }
	$plist = new CFPropertyList();
	$plist->add( $dict = new CFDictionary() );
        
    // Add manifest to production catalog by default
    $dict->add( 'catalogs', $array = new CFArray() );
    $array->add( new CFString( $catalog ) );
        
    // Add parent manifest to included_manifests to achieve waterfall effect
    $dict->add( 'included_manifests', $array = new CFArray() );
    if ( $identifier1 != "" )
        {
            $array->add( new CFString( $identifier1 ) );
        }
    if ( $identifier2 != "" )
        {
            $array->add( new CFString( $identifier2 ) );
        }
    if ( $identifier3 != "" )
        {
            $array->add( new CFString( $identifier3 ) );
        }
    if ( $identifier4 != "" )
        {
            $array->add( new CFString( $identifier4 ) );
        }


    // Save the newly created plist
    $plist->saveXML( '../manifests/client-' . $hostname );
    chmod( '../manifests/client-' . $hostname, 0775 );
    echo "\tNew manifest created: client-" . $hostname . "\n";
    echo "\tIncluded Manifest(s): " . $identifier1 . " " . $identifier2 . " " . $identifier3 . " " . $identifier4 . "\n";
        
?>
