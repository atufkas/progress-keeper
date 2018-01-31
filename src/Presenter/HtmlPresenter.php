<?php

namespace atufkas\ProgressKeeper\Presenter;

/**
 * Class HtmlPresenter
 * @package atufkas\ProgressKeeper\Presenter
 */
class HtmlPresenter implements PresenterInterface
{
    protected $pkLog;
    protected $htmlData;

    /**
     * @return mixed
     */
    public function transform($pkLog)
    {
        $this->htmlData .= '<div>';
        $this->htmlData .= '<h1 class="pklog-name">' . $pkLog->name . '</h1>';
        $this->htmlData .= '<span class="pklog-desc">' . $pkLog->desc . '</span>';
        $this->htmlData .= '<ul class="pklog-versions">';

        foreach ($pkLog->releases as $release) {
            $this->htmlData .= '<li>';
            $this->htmlData .= '<h2 class="pklog-release-version">' . $release->version . '</h2>';
            $this->htmlData .= '<span class="pklog-release-date">[ ' . $release->date . ' ]</span>';
            $this->htmlData .= '<span class="pklog-release-remarks">' . $release->remarks . '</span>';
            $this->htmlData .= '<ul>';

            foreach ($release->changelog as $logentry) {
                $this->htmlData .= '<li class="pklog-release-changelog">';
                $this->htmlData .= '<span class="pklog-release-changelog-type">[' . $logentry->type . ' ]</span>';
                $this->htmlData .= '&nbsp;';
                $this->htmlData .= '<span class="pklog-release-changelog-desc">' . $logentry->desc . '</span>';
                $this->htmlData .= '&nbsp;';
                $this->htmlData .= '<span class="pklog-release-changelog-date">[ ' . $logentry->date . ' ]</span>';
                $this->htmlData .= '</li>';
            }

            $this->htmlData .= '</ul>';
            $this->htmlData .= '</li>';
        }

        $this->htmlData .= '</ul>';
        $this->htmlData .= '</div>';

        return $this->getHtmlData();
    }

    /**
     * @return mixed
     */
    public function getHtmlData()
    {
        return $this->htmlData;
    }

    /**
     * @param mixed $htmlData
     */
    public function setHtmlData($htmlData)
    {
        $this->htmlData = $htmlData;
    }
}