<?php

namespace atufkas\ProgressKeeper\Tests;


use atufkas\ProgressKeeper\Presenter\HtmlPresenter;

/**
 * Class PresenterTest
 * @package atufkas\ProgressKeeper\Tests
 */
class PresenterTest extends JsonSampleTestCase
{
    /**
     * @test
     * @throws \atufkas\ProgressKeeper\ChangelogException
     * @throws \atufkas\ProgressKeeper\LogEntry\LogEntryException
     * @throws \atufkas\ProgressKeeper\Release\ReleaseException
     */
    public function testHtmlPresenter()
    {
        $htmlPresenter = new HtmlPresenter();
        $htmlPresenter->setChangelog(static::getChangelogFromSampleFile());
        $htmlChangelog = $htmlPresenter->getOutput();

        $this->assertStringStartsWith('<div class="pk">', $htmlChangelog);
        $this->assertStringEndsWith("</div>\n", $htmlChangelog);
    }
}
