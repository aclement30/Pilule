<?php
App::import('Vendor', 'HttpFetcher' );
App::import('Vendor', 'Capsule' );
App::import('Vendor', 'domparser' );

class UpdateUniversityCourseShell extends AppShell {

	public $uses = array( 'UniversityCourse' );
	public $Capsule;
	public $HttpFetcher;
	public $domparser;

    public function main() {
    	// Load HTTP Fetcher, DOM Parser and Capsule libraries in Controller
		$this->HttpFetcher = new HttpFetcher;
		$this->domparser = new domparser;
		$this->Capsule = new Capsule( $this->HttpFetcher, $this->domparser );

    	// Increase memory limit
		ini_set( 'memory_limit', '100M' );

		$this->stdout->styles( 'success', array( 'text' => 'green' ) );
		$this->stdout->styles( 'error', array( 'text' => 'red' ) );

		// Define semester
    	$semester = $this->args[ 0 ];
    	if ( !in_array( $semester, array( '201301', '201305', '201309', '201401', '201405', '201409' ) ) ) {
    		$this->out( '<error>Erreur : semestre invalide (' . $semester . ') !</error>' );
    		return false;
    	}

    	$initialTime = time();

    	$courses = $this->UniversityCourse->find( 'all', array(
    		'conditions'	=>	array( 'OR' => array( 'checkup_' . $semester . ' <=' => time() - 3600 * 24 * 7, 'checkup_' . $semester . ' IS NULL' ) ),
    		'limit'			=>	100
    	) );

        $this->out( 'Analyse de ' . count( $courses ) . ' cours' );
    	$this->out( '---------------------------------------------------------------' );

        foreach ( $courses as $index => $course ) {
            // Delete all existing classes for this course
            $this->UniversityCourse->Class->deleteAll( array( 'Class.course_id' => $course[ 'UniversityCourse' ][ 'id' ], 'Class.semester' => $semester ) );

            // Update course availability info
            $classes = $this->Capsule->fetchClasses( $course[ 'UniversityCourse' ][ 'code' ], $semester );

            if ( !empty( $classes[ 'Class' ] ) ) {
                // Save newly fetched classes for this course
                $course[ 'Class'] = $classes[ 'Class' ];
                $course[ 'UniversityCourse' ][ 'checkup_' . $semester ] = time();
                $course[ 'UniversityCourse' ][ 'av' . $semester ] = true;
                $this->UniversityCourse->set( $course );
                $this->UniversityCourse->saveAll( $course );

                $this->out( $index .  '. ' . $course[ 'UniversityCourse' ][ 'title' ] . ' | <success>OUI</success>' );
            } else {
                // Update course availability info
                $course[ 'UniversityCourse' ][ 'checkup_' . $semester ] = time();
                $course[ 'UniversityCourse' ][ 'av' . $semester ] = false;
                $this->UniversityCourse->set( $course );
                $this->UniversityCourse->saveAll( $course );

                $this->out( $index . '. ' . $course[ 'UniversityCourse' ][ 'title' ] . ' | <error>NON</error>' );
            }
        }

        $this->out( '---------------------------------------------------------------' );
        $this->out( 'Temps total : ' . ( time() - $initialTime ) / 60 . ' min.' );
    	$this->out( '---------------------------------------------------------------' );
    }

}