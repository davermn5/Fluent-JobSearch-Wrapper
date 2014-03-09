<?php

	/**
	 * This interface is used to create an abstract dependency between the
	 *  cbapi helper and cbapi model classes.
	 */
	interface IHelperSignature
	{
		public function getResponse($request_url, $returnType);
		public function applyFilters($haystack, $filters);
	}