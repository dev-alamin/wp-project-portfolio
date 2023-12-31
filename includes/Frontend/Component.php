<?php
namespace WPPP\Frontend;

class Component{
    /**
     * Generate the caption for a project.
     *
     * @return string The caption HTML.
     */
    public function caption( $id ): string{
        $caption = '<h4>' .esc_html__( get_the_title( $id ) ) . '</h4>';
        $caption .= '<p>' . esc_html__($this->short_description( $id, 50 ) );
        $caption .= '<a class="readmore" href="' . esc_url( get_the_permalink( $id ) ) . '">';
        $caption .= __( 'Read more', 'wp-project-portfolio' );
        $caption .= '</a>';
        return $caption;
    }

    /**
     * Generate shorter version of the_content()
     *
     * @param integer $words
     * @return string
     */
    private function short_description( int $id, int $words ): string {
        $shorter_cont = wp_trim_words( get_the_excerpt( $id ), $words, null );

        return $shorter_cont;
    }

    /**
     * Generate the content for a project.
     *
     * @param string $shorter_cont The shorter content to display.
     * @return string The content HTML.
     */
    public function content( $id ){
        $content = '<div class="text">';
        $content .= '<h4 class="project-title">' . get_the_title( $id ) . '</h4>';
        $content .= '<p>' . esc_html__( $this->short_description( $id, 20 ) ) . '</p>';
        $content .= '<span class="open-popup">' . __('View', 'wp-project-portfolio') . '</span>';
        $content .= '</div>';
        return $content;
    }

    /**
     * Generate the thumbnail for a project.
     *
     * @return string The thumbnail HTML.
     */
    public function thumbnail( $id ){
        $thumbnail = '<div class="thumb" style="background-image: url(' . get_the_post_thumbnail_url( $id , 'large') . ');"></div>';
        return $thumbnail;
    }

    /**
     * Render the filter dropdown for project categories.
     *
     * @return void
     */
    public function filter() {
        ?>
        <select id="sort-by-category" class="form-select w-25 d-inline" aria-label="<?php _e( 'Default select', 'wp-project-portfolio' ); ?>">
        <option selected><?php _e( 'Filter by category', 'wp-project-portfolio' ); ?></option>    
        <option value="all"><?php _e( 'All Categories', 'wp-project-portfolio' ); ?></option>

            <?php
            $categories = get_terms(
                array(
                    'taxonomy'   => 'project_cat', // Replace 'project_cat' with your custom taxonomy slug
                    'hide_empty' => true,
                )
            );

            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                foreach ( $categories as $category ) {
                    echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
                }
            }
            ?>
        </select>
        <?php
    }

    /**
     * Render the sorting dropdown for project titles.
     *
     * @return void
     */
    public function sort() {
        ?>
        <select id="sort-by-title" class="form-select w-25 d-inline" aria-label="<?php _e( 'Default select', 'wp-project-portfolio' ); ?>">
            <option selected disabled value="asc"><?php _e( 'Sort by Title', 'wp-project-portfolio' ); ?></option>
            <option value="asc"><?php _e( 'Sort by Title (A-Z)', 'wp-project-portfolio' ); ?></option>
            <option value="desc"><?php _e( 'Sort by Title (Z-A)', 'wp-project-portfolio' ); ?></option>
        </select>
        <?php
    }

    /**
     * Retrieve project loop data including unique categories, project IDs, and category counts.
     *
     * @return array Associative array containing categories, project IDs, and category counts.
     */
    public function loop() {
        $args = array(
            'post_type'      => 'portfolio_project',
            'post_status'    => 'publish',
            'posts_per_page' => 15,
        );

        $project = new \WP_Query($args);

        $unique_categories = array();
        $project_ids = array(); // Store post IDs here
        $category_counts = [];

        if ($project->have_posts()) {
            while ($project->have_posts()) {
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');

                if (!empty($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        $unique_categories[$term->slug] = $term->name;

                        if (isset($category_counts[$term->slug])) {
                            $category_counts[$term->slug]++;
                        } else {
                            $category_counts[$term->slug] = 1;
                        }
                    }
                }

                // Store post IDs
                $project_ids[] = get_the_ID();
            }

            
            return [
                'categories' => $unique_categories,
                'ids' => $project_ids,
                'cat_counts' => $category_counts,
            ];
            wp_reset_postdata();
        }
    }

    public function cat_counts() {
        $args = array(
            'post_type'      => 'portfolio_project',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );

        $project = new \WP_Query($args);

        $category_counts = [];

        if ( $project->have_posts() ) {
            while ( $project->have_posts() ) {
                $project->the_post();
                $terms = get_the_terms(get_the_ID(), 'project_cat');

                if ( ! empty( $terms ) && !is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        if ( isset( $category_counts[$term->slug ])) {
                            $category_counts[$term->slug]++;
                        } else {
                            $category_counts[$term->slug] = 1;
                        }
                    }
                }
            }

            
            return $category_counts;
            wp_reset_postdata();
        }
    }

    /**
     * Determine the Bootstrap class for a given index.
     *
     * @param int $index The index to determine the class for.
     * @return string The Bootstrap class for the given index.
     */
    public function Bootstrap_Class($index = 1) {
        $class = '';
        if ($index === 2 || $index === 7 || $index === 10) {
            $class = 'col-lg-8';
        } elseif ($index % 3 === 0 && $index != 9 && $index != 12) {
            $class = 'col-lg-8';
        } else {
            $class = 'col-lg-4';
        }

        return $class;
    }
}
